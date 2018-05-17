<?php
/************************************************
* 本程序于2018年03月25日
* 由陈浩波编写完成
* 任何人使用时请保留该声明
*/
namespace SPOOL\ZOOKEEPER\CLIENT;

include_once 'zookeeper.jute.php';
include_once 'zookeeper_log.php';
include_once 'zk_adaptor.php';
include_once 'zk_hashtable.php';
include_once 'st_adaptor.php';
/*
if(!defined('THREADED')){
    include_once 'st_adaptor.php';
}else{
    include_once 'mt_adaptor.php';
}
//*/

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_BAIL, 1);

function state2String(int $state){
    switch($state){
    case 0:
        return "ZOO_CLOSED_STATE";
    case CONNECTING_STATE_DEF:
        return "ZOO_CONNECTING_STATE";
    case ASSOCIATING_STATE_DEF:
        return "ZOO_ASSOCIATING_STATE";
    case CONNECTED_STATE_DEF:
        return "ZOO_CONNECTED_STATE";
    case EXPIRED_SESSION_STATE_DEF:
        return "ZOO_EXPIRED_SESSION_STATE";
    case AUTH_FAILED_STATE_DEF:
        return "ZOO_AUTH_FAILED_STATE";
    }
    return "INVALID_STATE";
}
function watcherEvent2String(int $ev){
    switch($ev){
    case 0:
        return "ZOO_ERROR_EVENT";
    case CREATED_EVENT_DEF:
        return "ZOO_CREATED_EVENT";
    case DELETED_EVENT_DEF:
        return "ZOO_DELETED_EVENT";
    case CHANGED_EVENT_DEF:
        return "ZOO_CHANGED_EVENT";
    case CHILD_EVENT_DEF:
        return "ZOO_CHILD_EVENT";
    case SESSION_EVENT_DEF:
        return "ZOO_SESSION_EVENT";
    case NOTWATCHING_EVENT_DEF:
        return "ZOO_NOTWATCHING_EVENT";
    }
    return "INVALID_EVENT";
}
$ZOO_ANYONE_ID_UNSAFE = new Id("world", "anyone");
$ZOO_AUTH_IDS = new Id("auth", "");
$_OPEN_ACL_UNSAFE_ACL = new ACL(0x1f, $ZOO_ANYONE_ID_UNSAFE);
$_READ_ACL_UNSAFE_ACL = new ACL(0x01, $ZOO_ANYONE_ID_UNSAFE);
$_CREATOR_ALL_ACL_ACL = new ACL(0x1f, $ZOO_AUTH_IDS);
$ZOO_OPEN_ACL_UNSAFE = new ACL_vector( 1, $_OPEN_ACL_UNSAFE_ACL);
$ZOO_READ_ACL_UNSAFE = new ACL_vector( 1, $_READ_ACL_UNSAFE_ACL);
$ZOO_CREATOR_ALL_ACL = new ACL_vector( 1, $_CREATOR_ALL_ACL_ACL);

function err2string(int $err): string{
}
//if(defined('THREADED')){
// IO thread queues session events to be processed by the completion thread
function queue_session_event(zhandle_t $zh, int $state): int 
{
    $rc = 0;
//        struct WatcherEvent evt = { ZOO_SESSION_EVENT, state, "" };
//        struct ReplyHeader hdr = { WATCHER_EVENT_XID, 0, 0 };
//        struct oarchive *oa;
//        completion_list_t *cptr;
    $evt = new WatcherEvent(ZOO_SESSION_EVENT, $state, "");
    $hdr = new ReplyHeader(WATCHER_EVENT_XID, 0, 0);
    $oa = new oarchive();
    $cptr = new completion_list_t();

    if (($oa=create_buffer_oarchive())==NULL) {
        LOG_ERROR(("out of memory"), __LINE__, __FUNCTION__);
        goto error;
    }
    $rc = Serialize::serialize_ReplyHeader($oa, "hdr", $hdr);
    $rc = $rc < 0 ? rc : Serialize::serialize_WatcherEvent($oa, "event", $evt);
    if($rc<0){
        close_buffer_oarchive($oa, 1);
        goto error;
    }
    $cptr = create_completion_entry(WATCHER_EVENT_XID,-1,0,0,0,0);
    $cptr->buffer = allocate_buffer(get_buffer($oa), get_buffer_len($oa));
    $cptr->buffer->curr_offset = get_buffer_len($oa);
    if (!$cptr->buffer) {
//            free(cptr);
        $cptr = null;
        close_buffer_oarchive($oa, 1);
        goto error;
    }
    /* We queued the buffer, so don't free it */
    close_buffer_oarchive($oa, 0);
    $cptr->c->watcher_result = collectWatchers($zh, ZOO_SESSION_EVENT, "");
    queue_completion($zh->completions_to_process, $cptr, 0);
    if (process_async($zh->outstanding_sync)) {
        process_completions($zh);
    }
    return ZOK;
error:
    $errno = ENOMEM;
    return ZSYSTEMERROR;
}

/* sockaddr_storage $ep */
function format_endpoint_info(sockaddr_storage &$ep): string{
    if(!$ep->padding) return "null";
    return $ep->padding;
}
function format_current_endpoint_info(zhandle_t &$zh): string{
    return format_endpoint_info($zh->addrs[$zh->connect_index]);
}

/* deserialize forward declarations */

function deserialize_multi(int $xid, completion_list_t &$cptr, iarchive &$ia) : int{
    $rc = 0;
//    completion_head_t *clist = &cptr->c.clist;
    $clist = new completion_head_t();
    $clist = $cptr->c->clist;
//    struct MultiHeader mhdr = { STRUCT_INITIALIZER(type , 0), STRUCT_INITIALIZER(done , 0), STRUCT_INITIALIZER(err , 0) };
    $mhdr = new MultiHeader(0, 0, 0);
    assert($clist);
    Serialize::deserialize_MultiHeader($ia, "multiheader", $mhdr);
    while (!$mhdr->done) {
//        completion_list_t *entry = dequeue_completion(clist);
        $entry = new completion_list_t();
        $entry = dequeue_completion($clist);
        assert($entry);

        if ($mhdr->type == -1) {
//            struct ErrorResponse er;
            $er = new ErrorResponse();
            Serialize::deserialize_ErrorResponse($ia, "error", $er);
            $mhdr->err = $er->err ;
            if ($rc == 0 && $er->err != 0 && $er->err != ZRUNTIMEINCONSISTENCY) {
                $rc = $er->err;
            }
        }

        deserialize_response($entry->c->type, $xid, $mhdr->type == -1, $mhdr->err, $entry, $ia);
        Serialize::deserialize_MultiHeader($ia, "multiheader", $mhdr);
        //While deserializing the response we must destroy completion entry for each operation in 
        //the zoo_multi transaction. Otherwise this results in memory leak when client invokes zoo_multi
        //operation.
        destroy_completion_entry($entry);
    }

    return $rc;
}

function handle_socket_error_msg(zhandle_t &$zh, int $line, int $rc, string $format, ...$param) : int{
}
function enter_critical(zhandle_t &$zh) : int
{
    $adaptor = $zh->adaptor_priv;
    //暂时按单进程实现
    if ($adaptor) {
        //暂时没有好的处理方案，保留
        return 0;
        return pthread_mutex_lock($adaptor->zh_lock);
    } else {
        return 0;
    }
}
function dequeue_buffer(buffer_head_t &$list) : buffer_list_t
{
    lock_buffer_list($list);
    $b = $list->head;
    if ($b) {
        $list->head = $b->next();
        if (!$list->head) {
            $list->last = 0;
        }
    }
    unlock_buffer_list($list);
    if(!$b){
        //php7指定了函数返回类型后，居然不能返回null，好吧，那就给你一个空的结构好了。
        $b = new buffer_list_t();
    }
    return $b;
}
function free_buffer(buffer_list_t &$b)
{
    if (!$b) {
        return;
    }
    $b = null;
}
function remove_buffer(buffer_head_t &$list) : int
{
    $b = dequeue_buffer($list);
    if (!$b->buffer) {
        return 0;
    }
    free_buffer($b);
    return 1;
}
function free_buffers(buffer_head_t &$list)
{
    while (remove_buffer($list))
        ;
}

function free_completions(zhandle_t &$zh, int $callCompletion, int $reason)
{
/*
    completion_head_t tmp_list;
    struct oarchive *oa;
    struct ReplyHeader h;
    void_completion_t auth_completion = NULL;
    auth_completion_list_t a_list, *a_tmp;
//*/
    $tmp_list = new completion_head_t();
    $os = new oarchive();
    $h = new ReplyHeader();
    $auth_completion = new void_completion_t();
    $a_list = new auth_completion_list_t();
    $a_tmp = new auth_completion_list_t();
    if (lock_completion_list($zh->sent_requests) == 0) {
        $tmp_list = $zh->sent_requests;
        $zh->sent_requests->head = 0;
        $zh->sent_requests->last = 0;
        unlock_completion_list($zh->sent_requests);
        while($tmp_list->head){
            $cptr = new _completion_list();
            $cptr = &$tmp_list->head;
            $tmp_list->head = $cptr->next();
            if (is_callable( $cptr->c->data_result) ) {
                $sc = $cptr->data;          //class sync_completion
                $sc->rc = $reason;
                notify_sync_completion($sc);
                $zh->outstanding_sync--;
                destroy_completion_entry($cptr);
            } else if ($callCompletion) {
                // Fake the response
//                buffer_list_t *bptr;
                $bptr = new buffer_list_t();
                $h->xid = $cptr->xid;
                $h->zxid = -1;
                $h->err = $reason;
                $oa = create_buffer_oarchive();
                serialize_ReplyHeader($oa, "header", $h);
//                bptr = calloc(sizeof(*bptr), 1);
//                assert(bptr);
                $bptr->len = get_buffer_len($oa);
                $bptr->buffer = get_buffer($oa);
                close_buffer_oarchive($oa, 0);
                $cptr->buffer = $bptr;
                queue_completion($zh->completions_to_process, $cptr, 0);
            }
        }
    }
    if (zoo_lock_auth($zh) == 0) {
        $a_list->completion = NULL;
        $a_list->next = NULL;
        
        get_auth_completions($zh->auth_h, $a_list);
        zoo_unlock_auth($zh);
    
        $a_tmp = $a_list;
        // chain call user's completion function
        while($a_tmp->completion != NULL){
            $auth_completion = $a_tmp->completion;
            $auth_completion($reason, $a_tmp->auth_data);
            $a_tmp = $a_tmp->next();
            if($a_tmp == null){
                break;
            }
        }
        unset($a_tmp);
    }
    free_auth_completion($a_list);
}
function leave_critical(zhandle_t &$zh) : int
{
    return 0;
}
function cleanup_bufs(zhandle_t &$zh,int $callCompletion,int $rc)
{
    enter_critical($zh);
    free_buffers($zh->to_send);
    free_buffers($zh->to_process);
    free_completions($zh,$callCompletion,$rc);
    leave_critical($zh);
    if ($zh->input_buffer && $zh->input_buffer != $zh->primer_buffer) {
        free_buffer($zh->input_buffer);
        $zh->input_buffer = 0;
    }
}
function handle_error(zhandle_t $zh,int $rc)
{
//    close(zh->fd);
    //这里需要再确认一下到底是使用socket还是swoole
    //线程部分还是使用swoole比较好，pthreads要求PHP的版本在7.2以上，还必须是zts版本，对于大多数用户来说，要求比较高。
    //在全复原的情况下，或许用socket更好些
    socket_close($zh->fd);
    if (is_unrecoverable($zh)) {
        LOG_DEBUG(sprintf("Calling a watcher for a ZOO_SESSION_EVENT and the state=%s" . state2String($zh->state)), __LINE__, __FUNCTION__);
        PROCESS_SESSION_EVENT($zh, $zh->state);
    } else if ($zh->state == ZOO_CONNECTED_STATE) {
        LOG_DEBUG(sprintf("Calling a watcher for a ZOO_SESSION_EVENT and the state=CONNECTING_STATE"), __LINE__, __FUNCTION__);
        PROCESS_SESSION_EVENT($zh, ZOO_CONNECTING_STATE);
    }
    cleanup_bufs($zh, 1, $rc);
    $zh->fd = -1;
    $zh->connect_index++;
    if (!is_unrecoverable($zh)) {
        $zh->state = 0;
    }
    if (process_async($zh->outstanding_sync)) {
        process_completions($zh);
    }
}
$disable_conn_permute = 0; // permute enabled by default
function print_completion_queue(zhandle_t &$zh){
//    completion_list_t* cptr;
    $cptr = new completion_list_t();
    if($logLevel<ZOO_LOG_LEVEL_DEBUG) return;

    fprintf(LOGSTREAM,"Completion queue: ");
    if (!$zh->sent_requests->head) {
        fprintf(LOGSTREAM,"empty\n");
        return;
    }

    $cptr = $zh->sent_requests->head;
    while($cptr){
        fprintf(LOGSTREAM,"%d,",$cptr->xid);
        $cptr = $cptr->next;
    }
    fprintf(LOGSTREAM,"end\n");
}
/* $s是一个socket描述符，或者应该用fp来描述？ */
function zookeeper_send(int $s, &$buf, int $len) : int {
//    var_dump(__LINE__, __FUNCTION__, $s, $buf, $len);
    return socket_write($s, $buf, $len);
}
function zoo_get_context(zhandle_t &$zh)
{
    return $zh->context;
}
function zoo_set_context(zhandle_t &$zh, &$context)
{
    if ($zh != NULL) {
        $zh->context = $context;
    }
}
function zoo_recv_timeout(zhandle_t &$zh) : int
{
    return $zh->recv_timeout;
}

function allocate_buffer(string &$buff, int $len) : buffer_list_t
{
//    buffer_list_t *buffer = calloc(1, sizeof(*buffer));
    $buffer = new buffer_list_t();
    if (!$buffer)
        return 0;

    $buffer->len = $len == 0 ? 0 : $len;
    $buffer->curr_offset = 0;
    $buffer->buffer = buff;
    $buffer->next = 0;
    return $buffer;
}
/*
function create_watcher_object_list(watcher_object_t &$head) : watcher_object_list_t
{
//    watcher_object_list_t* wl=calloc(1,sizeof(watcher_object_list_t));
    $wl = new watcher_object_list();
//    assert(wl);
    $wl->head = $head;
    return $wl;
}
//*/
function process_completions(zhandle_t &$zh)
{
//    completion_list_t *cptr;
    while ($cptr = dequeue_completion($zh->completions_to_process)) {
//        struct ReplyHeader hdr;
//        buffer_list_t *bptr = cptr->buffer;
//        struct iarchive *ia = create_buffer_iarchive(bptr->buffer, bptr->len);
        
        $hdr = new ReplyHeader(0, 0, 0);
        $bptr = $cptr->buffer;
        $ia = create_buffer_iarchive($bptr->buffer, $bptr->len);
        
        Serialize::deserialize_ReplyHeader($ia, "hdr", $hdr);

        if ($hdr->xid == WATCHER_EVENT_XID) {
//            int type, state;
//            struct WatcherEvent evt;
            $type = $state = 0;
            $evt = new WatcherEvent();
            Serialize::deserialize_WatcherEvent($ia, "event", $evt);
            /* We are doing a notification, so there is no pending request */
//            type = evt.type;
//            state = evt.state;
            $type = $evt->type;
            $state = $evt->state;
            
            /* This is a notification so there aren't any pending requests */
//            LOG_DEBUG(("Calling a watcher for node [%s], type = %d event=%s", (evt.path==NULL?"NULL":evt.path), cptr->c.type, watcherEvent2String(type)));
            LOG_DEBUG(sprintf("Calling a watcher for node [%s], type = %d event=%s", $evt->path ? $evt->path : 'NULL', $cptr->c->type, watcherEvent2String($type)), __LINE__, __FUNCTION__);
            deliverWatchers($zh, $type, $state, $evt->path, $cptr->c->watcher_result);
            serialize::deallocate_WatcherEvent($evt);
        } else {
            deserialize_response($cptr->c->type, $hdr->xid, $hdr->err != 0, $hdr->err, $cptr, $ia);
        }
        destroy_completion_entry($cptr);
        close_buffer_iarchive($ia);
    }
}

/**
 * deallocated the free_path only its beeen allocated
 * and not equal to path
 */
function free_duplicate_path(string &$free_path, string &$path) {
    if ($free_path != $path) {
        $free_path = null;
    }
}

function dequeue_completion(completion_head_t &$list) : completion_list_t
{
//    completion_list_t *cptr;
    $cptr = new completion_head_t();
    lock_completion_list($list);
    /*
    $cptr = $list->head;
    if ($cptr) {
        list->head = cptr->next;
        if (!list->head) {
            assert(list->last == cptr);
            list->last = 0;
        }
    }
    //*/
    if($list->head){
        $cptr = array_shift($list->head);
    }else{
        $cptr = null;
    }
    unlock_completion_list($list);
    return $cptr;
}

/**
 * Frees and closes everything associated with a handle,
 * including the handle itself.
 */
function destroy(zhandle_t &$zh)
{
    if ($zh == NULL) {
        return;
    }
    /* call any outstanding completions with a special error code */
    cleanup_bufs($zh,1,ZCLOSING);
    if ($zh->hostname != 0) {
//        free(zh->hostname);
        $zh->hostname = NULL;
    }
    if ($zh->fd != -1) {
        //使用socket时
        socket_close($zh->fd);
        //使用swoole时
        //
//        close(zh->fd);
        $zh->fd = -1;
        $zh->state = 0;
    }
    if ($zh->addrs != 0) {
//        free(zh->addrs);
        $zh->addrs = NULL;
    }

    if ($zh->chroot != 0) {
//        free(zh->chroot);
        $zh->chroot = NULL;
    }

    free_auth_info($zh->auth_h);
    destroy_zk_hashtable($zh->active_node_watchers);
    destroy_zk_hashtable($zh->active_exist_watchers);
    destroy_zk_hashtable($zh->active_child_watchers);
}

function sub_string(zhandle_t &$zh, string $server_path) : string
{
    $ret_str = '';
    if (!$zh->chroot)
        return $server_path;
    //ZOOKEEPER-1027
    if (strncmp($server_path, $zh->chroot, strlen($zh->chroot)) != 0) {
        LOG_ERROR(sprintf("server path %s does not include chroot path %s", $server_path, $zh->chroot), __LINE__, __FUNCTION__);
        return $server_path;
    }
    if (strlen($server_path) == strlen($zh->chroot)) {
        //return "/"
        $ret_str = "/";
        return $ret_str;
    }
    $ret_str = substr($server_path, strlen($zh->chroot));
    return $ret_str;
}

function deserialize_response(int $type, int $xid, int $failed, int $rc, completion_list_t &$cptr, iarchive &$ia)
{
    switch ($type) {
    case COMPLETION_DATA:
//        LOG_DEBUG(("Calling COMPLETION_DATA for xid=%#x failed=%d rc=%d", cptr->xid, failed, rc));
        LOG_DEBUG(sprintf("Calling COMPLETION_DATA for xid=%#x failed=%d rc=%d", $cptr->xid, $failed, $rc), __LINE__, __FUNCTION__);
        if ($failed) {
            $cptr->c->data_result($rc, 0, 0, 0, $cptr->data);
        } else {
//            struct GetDataResponse res;
            $res = new GetDataResponse();
            serialize::deserialize_GetDataResponse($ia, "reply", $res);
            $cptr->c->data_result($rc, $res->data->buff, $res->data->len, $res->stat, $cptr->data);
            serialize::deallocate_GetDataResponse($res);
        }
        break;
    case COMPLETION_STAT:
        LOG_DEBUG(sprintf("Calling COMPLETION_STAT for xid=%#x failed=%d rc=%d", $cptr->xid, $failed, $rc), __LINE__, __FUNCTION__);
        if ($failed) {
            $cptr->c->stat_result($rc, 0, $cptr->data);
        } else {
//            struct SetDataResponse res;
            $res = new SetDataResponse();
            serialize::deserialize_SetDataResponse($ia, "reply", $res);
            $cptr->c->stat_result($rc, $res->stat, $cptr->data);
            serialize::deallocate_SetDataResponse($res);
        }
        break;
    case COMPLETION_STRINGLIST:
        LOG_DEBUG(sprintf("Calling COMPLETION_STRINGLIST for xid=%#x failed=%d rc=%d", $cptr->xid, $failed, $rc), __LINE__, __FUNCTION__);
        if ($failed) {
            $cptr->c->strings_result($rc, 0, $cptr->data);
        } else {
//            struct GetChildrenResponse res;
            $res = new GetChildrenResponse();
            serialize::deserialize_GetChildrenResponse($ia, "reply", $res);
            $cptr->c->strings_result($rc, $res->children, $cptr->data);
            serialize::deallocate_GetChildrenResponse($res);
        }
        break;
    case COMPLETION_STRINGLIST_STAT:
        LOG_DEBUG(sprintf("Calling COMPLETION_STRINGLIST_STAT for xid=%#x failed=%d rc=%d", $cptr->xid, $failed, $rc), __LINE__, __FUNCTION__);
        if ($failed) {
            $cptr->c->strings_stat_result($rc, 0, 0, $cptr->data);
        } else {
//            struct GetChildren2Response res;
            $res = new GetChildren2Response();
            serialize::deserialize_GetChildren2Response($ia, "reply", $res);
            $cptr->c->strings_stat_result($rc, $res->children, $res->stat, $cptr->data);
            serialize::deallocate_GetChildren2Response($res);
        }
        break;
    case COMPLETION_STRING:
        LOG_DEBUG(sprintf("Calling COMPLETION_STRING for xid=%#x failed=%d, rc=%d", $cptr->xid, $failed, $rc), __LINE__, __FUNCTION__);
        if ($failed) {
            $cptr->c->string_result($rc, 0, $cptr->data);
        } else {
//            struct CreateResponse res;
            $res = new CreateResponse();
//            memset(&res, 0, sizeof(res));
            serialize::deserialize_CreateResponse($ia, "reply", $res);
            $cptr->c->string_result($rc, $res->path, $cptr->data);
            serialize::deallocate_CreateResponse($res);
        }
        break;
    case COMPLETION_ACLLIST:
        LOG_DEBUG(sprintf("Calling COMPLETION_ACLLIST for xid=%#x failed=%d rc=%d", $cptr->xid, $failed, $rc), __LINE__, __FUNCTION__);
        if ($failed) {
            $cptr->c->acl_result($rc, 0, 0, $cptr->data);
        } else {
//            struct GetACLResponse res;
            $res = new GetACLResponse();
            serialize::deserialize_GetACLResponse($ia, "reply", $res);
            $cptr->c->acl_result($rc, $res->acl, $res->stat, $cptr->data);
            serialize::deallocate_GetACLResponse($res);
        }
        break;
    case COMPLETION_VOID:
        LOG_DEBUG(sprintf("Calling COMPLETION_VOID for xid=%#x failed=%d rc=%d", $cptr->xid, $failed, $rc), __LINE__, __FUNCTION__);
        assert($cptr->c->void_result);
        $cptr->c->void_result($rc, $cptr->data);
        break;
    case COMPLETION_MULTI:
        LOG_DEBUG(sprintf("Calling COMPLETION_MULTI for xid=%#x failed=%d rc=%d", $cptr->xid, $failed, $rc), __LINE__, __FUNCTION__);
        $rc = serialize::deserialize_multi($xid, $cptr, $ia);
        assert($cptr->c->void_result);
        $cptr->c->void_result($rc, $cptr->data);
        break;
    default:
        LOG_DEBUG(sprintf("Unsupported completion type=%d", $cptr->c->type), __LINE__, __FUNCTION__);
    }
}

function isSocketReadable(zhandle_t &$zh)
{
    /*
    fd_set rfds;
    struct timeval waittime = {0, 0};
    FD_ZERO(&rfds);
    FD_SET( zh->fd , &rfds);
    if (select(0, &rfds, NULL, NULL, &waittime) <= 0){
        // socket not readable -- no more responses to process
        zh->socket_readable.tv_sec=zh->socket_readable.tv_usec=0;
    }
    else{
        gettimeofday(&zh->socket_readable,0);
    }
    //*/
}

function checkResponseLatency(zhandle_t &$zh)
{
    /*
    int delay;
    struct timeval now;

    if(zh->socket_readable.tv_sec==0)
        return;

    gettimeofday(&now,0);
    delay=calculate_interval(&zh->socket_readable, &now);
    if(delay>20)
        LOG_DEBUG(("The following server response has spent at least %dms sitting in the client socket recv buffer",delay));

    zh->socket_readable.tv_sec=zh->socket_readable.tv_usec=0;
    //*/
}

function queue_buffer(buffer_head_t &$list, buffer_list_t &$b, int $add_to_front)
{
    $b->next = 0;
    lock_buffer_list($list);
    if ($list->head) {
        assert($list->last);
        // The list is not empty
        if ($add_to_front) {
            $b->next = $list->head;
            $list->head = $b;
        } else {
            $list->last->next = $b;
            $list->last = $b;
        }
    }else{
        // The list is empty
        assert(!$list->head);
        $list->head = $b;
        $list->last = $b;
    }
    unlock_buffer_list($list);
}

function queue_buffer_bytes(buffer_head_t &$list, string $buff, int $len) : int
{
//    buffer_list_t *b  = allocate_buffer(buff,len);
    $b = new buffer_list_t($buff, $len);
    if (!$b)
        return ZSYSTEMERROR;
    queue_buffer($list, $b, 0);
    return ZOK;
}

function free_key_list(string &$list, int $count)
{
    /*
    int i;

    for(i = 0; i < count; i++) {
        free(list[i]);
    }
    free(list);
    //*/
    $list = null;
}

function queue_front_buffer_bytes(buffer_head_t &$list, string &$buff, int $len) : int
{
    /*
    buffer_list_t *b = allocate_buffer(buff,len);
    if (!b)
        return ZSYSTEMERROR;
    queue_buffer(list, b, 1);
    return ZOK;
    //*/
    $b = new buffer_list_t($buff, $len);
    queue_buffer($list, $b, 1);
    return ZOK;
}

function send_set_watches(zhandle_t &$zh) : int
{
    /*
    struct oarchive *oa;
    struct RequestHeader h = { STRUCT_INITIALIZER(xid , SET_WATCHES_XID), STRUCT_INITIALIZER(type , ZOO_SETWATCHES_OP)};
    struct SetWatches req;
    int rc;

    req.relativeZxid = zh->last_zxid;
    req.dataWatches.data = collect_keys(zh->active_node_watchers, (int*)&req.dataWatches.count);
    req.existWatches.data = collect_keys(zh->active_exist_watchers, (int*)&req.existWatches.count);
    req.childWatches.data = collect_keys(zh->active_child_watchers, (int*)&req.childWatches.count);

    // return if there are no pending watches
    if (!req.dataWatches.count && !req.existWatches.count &&
        !req.childWatches.count) {
        free_key_list(req.dataWatches.data, req.dataWatches.count);
        free_key_list(req.existWatches.data, req.existWatches.count);
        free_key_list(req.childWatches.data, req.childWatches.count);
        return ZOK;
    }


    oa = create_buffer_oarchive();
    rc = serialize_RequestHeader(oa, "header", &h);
    rc = rc < 0 ? rc : serialize_SetWatches(oa, "req", &req);
    /* add this buffer to the head of the send queue */
    /*
    rc = rc < 0 ? rc : queue_front_buffer_bytes(&zh->to_send, get_buffer(oa),
            get_buffer_len(oa));
    //*/
    /* We queued the buffer, so don't free it */ 
    /*  
    close_buffer_oarchive(&oa, 0);
    free_key_list(req.dataWatches.data, req.dataWatches.count);
    free_key_list(req.existWatches.data, req.existWatches.count);
    free_key_list(req.childWatches.data, req.childWatches.count);
    LOG_DEBUG(("Sending set watches request to %s",format_current_endpoint_info(zh)));
    return (rc < 0)?ZMARSHALLINGERROR:ZOK;
    //*/
    $oa = new oarchive();
    $h = new RequestHeader(SET_WATCHES_XID, ZOO_SETWATCHES_OP);
    $req = new SetWatches();
    $rc = 0;
    
    $req->relativeZxid = $zh->last_zxid;
    $req->dataWatches->data = collect_keys($zh->active_node_watchers, $req->dataWatches->count);
    $req->existWatches->data = collect_keys($zh->active_exist_watchers, $req->existWatches->count);
    $req->childWatches = collect_keys($zh->active_child_watchers, $req->childWatches->count);
    
    // return if there are no pending watches
    if (!$req->dataWatches->count && !$req->existWatches->count && !$req->childWatches->count) {
        free_key_list($req->dataWatches->data, $req->dataWatches->count);
        free_key_list($req->existWatches->data, $req->existWatches->count);
        free_key_list($req->childWatches->data, $req->childWatches->count);
        return ZOK;
    }
    
    $oa = create_buffer_oarchive();
    $rc = serialize::serialize_RequestHeader($oa, "header", $h);
    $rc = $rc < 0 ? $rc : serialize::serialize_SetWatches($oa, "req", $req);
    /* add this buffer to the head of the send queue */
    $rc = $rc < 0 ? $rc : queue_front_buffer_bytes($zh->to_send, get_buffer($oa),
            get_buffer_len($oa));
    /* We queued the buffer, so don't free it */   
    close_buffer_oarchive($oa, 0);
    free_key_list($req->dataWatches->data, $req->dataWatches->count);
    free_key_list($req->existWatches->data, $req->existWatches->count);
    free_key_list($req->childWatches->data, $req->childWatches->count);
    LOG_DEBUG(sprintf("Sending set watches request to %s",format_current_endpoint_info($zh)), __LINE__, __FUNCTION__);
    return ($rc < 0) ? ZMARSHALLINGERROR : ZOK;
}

function api_epilog(zhandle_t &$zh,int $rc) : int
{
    if(inc_ref_counter($zh,-1)==0 && $zh->close_requested!=0)
        zookeeper_close($zh);
    return $rc;
}

function serialize_prime_connect(connect_req &$req, string &$buffer) : int
{
    //this should be the order of serialization
    /*
    int offset = 0;
    req->protocolVersion = htonl(req->protocolVersion);
    memcpy(buffer + offset, &req->protocolVersion, sizeof(req->protocolVersion));
    offset = offset +  sizeof(req->protocolVersion);

    req->lastZxidSeen = zoo_htonll(req->lastZxidSeen);
    memcpy(buffer + offset, &req->lastZxidSeen, sizeof(req->lastZxidSeen));
    offset = offset +  sizeof(req->lastZxidSeen);

    req->timeOut = htonl(req->timeOut);
    memcpy(buffer + offset, &req->timeOut, sizeof(req->timeOut));
    offset = offset +  sizeof(req->timeOut);

    req->sessionId = zoo_htonll(req->sessionId);
    memcpy(buffer + offset, &req->sessionId, sizeof(req->sessionId));
    offset = offset +  sizeof(req->sessionId);

    req->passwd_len = htonl(req->passwd_len);
    memcpy(buffer + offset, &req->passwd_len, sizeof(req->passwd_len));
    offset = offset +  sizeof(req->passwd_len);

    memcpy(buffer + offset, req->passwd, sizeof(req->passwd));
    //*/
    
    $buffer = pack('NJNJNa16', $req->protocolVersion, $req->lastZxidSeen, $req->timeOut, $req->sessionId, $req->passwd_len , $req->passwd);
    $buffer .= str_pad($req->passwd, 16, "\0", STR_PAD_LEFT);

    return 0;
}

function prime_connection(zhandle_t &$zh) : int
{
//    int rc;
    /*this is the size of buffer to serialize req into*/
//    char buffer_req[HANDSHAKE_REQ_SIZE];
//    int len = sizeof(buffer_req);
//    int hlen = 0;
//    struct connect_req req;
    $rc = $len = $hlen = 0;
    $buffer_req = '';
    $req = new connect_req();
    $req->protocolVersion = 0;
    $req->sessionId = $zh->client_id->client_id;
    $req->passwd = $zh->client_id->passwd;
    $req->passwd_len = strlen($req->passwd);
    $req->timeOut = $zh->recv_timeout;
    $req->lastZxidSeen = $zh->last_zxid;
//    req.protocolVersion = 0;
//    req.sessionId = zh->client_id.client_id;
//    req.passwd_len = sizeof(req.passwd);
//    memcpy(req.passwd, zh->client_id.passwd, sizeof(zh->client_id.passwd));
//    req.timeOut = zh->recv_timeout;
//    req.lastZxidSeen = zh->last_zxid;
//    $hlen = htonl($len);
    /* We are running fast and loose here, but this string should fit in the initial buffer! */
    $rc = zookeeper_send($zh->fd, $len, 4);
    serialize_prime_connect($req, $buffer_req);
    $rc = $rc < 0 ? $rc : zookeeper_send($zh->fd, $buffer_req, $len);
    if ($rc < 0) {
        return handle_socket_error_msg($zh, __LINE__, ZCONNECTIONLOSS,
                "failed to send a handshake packet: %s", strerror($errno));
    }
    $zh->state = ZOO_ASSOCIATING_STATE;

    $zh->input_buffer = $zh->primer_buffer;
    /* This seems a bit weird to to set the offset to 4, but we already have a
     * length, so we skip reading the length (and allocating the buffer) by
     * saying that we are already at offset 4 */
    $zh->input_buffer->curr_offset = 4;
    
    return ZOK;
}

function recv_buffer(int $fd, buffer_list_t &$buff) : int
{
    $off = $buff->curr_offset;
    $rc = 0;
    //fprintf(LOGSTREAM, "rc = %d, off = %d, line %d\n", rc, off, __LINE__);

    /* if buffer is less than 4, we are reading in the length */
    if ($off < 4) {
//        char *buffer = (char*)&(buff->len);
//        rc = recv(fd, buffer+off, sizeof(int)-off, 0);
        $buffer = socket_read($fd, 4 - $off);
        if(!$buffer){
            $errno = EHOSTDOWN;
            $rc = socket_last_error($fd);
            if($rc == ENOBUFS){
                return 0;
            }
            return -1;
        }else{
            $rc = strlen($buffer);
            $buff->curr_offset += $rc;
//            $buff->buffer .= $buffer;
        }
        //fprintf(LOGSTREAM, "rc = %d, off = %d, line %d\n", rc, off, __LINE__);
        /*
        switch($rc) {
        case 0:
            $errno = EHOSTDOWN;
        case -1:
            if (WSAGetLastError() == WSAEWOULDBLOCK) {
                return 0;
            }
            return -1;
        default:
            buff->curr_offset += rc;
        }
        off = buff->curr_offset;
        if (buff->curr_offset == sizeof(buff->len)) {
            buff->len = ntohl(buff->len);
            buff->buffer = calloc(1, buff->len);
        }
        //*/
    }
    if ($buff->buffer) {
        /* want off to now represent the offset into the buffer */
//        off -= sizeof(buff->len);
        $off -= 4;
        $buffer = socket_read($fd, $buff->len - $off);
//        rc = recv(fd, buff->buffer+off, buff->len-off, 0);
        if(!$buffer){
            $errno = EHOSTDOWN;
            $rc = socket_last_error($fd);
            if($rc == ENOBUFS){
                return 0;
            }
            return -1;
        }else{
            $rc = strlen($buffer);
            $buff->curr_offset += $rc;
            $buff->buffer .= $buffer;
        }
        /*
        switch(rc) {
        case 0:
            errno = EHOSTDOWN;
        case -1:
            if (WSAGetLastError() == WSAEWOULDBLOCK) {
                break;
            }
            return -1;
        default:
            buff->curr_offset += rc;
        }
        //*/
    }
//    return buff->curr_offset == buff->len + sizeof(buff->len);
    return $buff->curr_offset == $buff->len + 4;
}

function deserialize_prime_response(prime_struct &$req, string &$buffer) : int
{
     //this should be the order of deserialization
     /*
     int offset = 0;
     memcpy(&req->len, buffer + offset, sizeof(req->len));
     offset = offset +  sizeof(req->len);

     req->len = ntohl(req->len);
     memcpy(&req->protocolVersion, buffer + offset, sizeof(req->protocolVersion));
     offset = offset +  sizeof(req->protocolVersion);

     req->protocolVersion = ntohl(req->protocolVersion);
     memcpy(&req->timeOut, buffer + offset, sizeof(req->timeOut));
     offset = offset +  sizeof(req->timeOut);

     req->timeOut = ntohl(req->timeOut);
     memcpy(&req->sessionId, buffer + offset, sizeof(req->sessionId));
     offset = offset +  sizeof(req->sessionId);

     req->sessionId = zoo_htonll(req->sessionId);
     memcpy(&req->passwd_len, buffer + offset, sizeof(req->passwd_len));
     offset = offset +  sizeof(req->passwd_len);

     req->passwd_len = ntohl(req->passwd_len);
     memcpy(req->passwd, buffer + offset, sizeof(req->passwd));
     //*/
     $buff = unpack("Nlen/NprotocolVersion/NtimeOut/JsessionId/Npasswd_len", $buffer);
     $req->len = $buff['len'];
     $req->protocolVersion = $buff['protocolVersion'];
     $req->timeOut = $buff['timeOut'];
     $req->sessionId = $buff['sessionId'];
     $req->passwd_len = $buff['passwd_len'];
     $req->passwd = trim(substr($buffer, 0 - $buff['passwd_len']));
     
     return 0;
}

function auth_completion_func(int $rc, zhandle_t &$zh)
{
    /*
    void_completion_t auth_completion = NULL;
    auth_completion_list_t a_list;
    auth_completion_list_t *a_tmp;
    //*/
    $auth_completion = new void_completion_t();
    $a_list = new auth_completion_list_t();
    $a_tmp = new auth_completion_list_t();
    if(!$zh)
        return;

    zoo_lock_auth($zh);

    if($rc != 0){
        $zh->state = ZOO_AUTH_FAILED_STATE;
    }else{
        //change state for all auths
        mark_active_auth($zh);
    }
    $a_list->completion = NULL;
    $a_list->next = NULL;
    get_auth_completions($zh->auth_h, $a_list);
    zoo_unlock_auth($zh);
    if ($rc) {
        LOG_ERROR(sprintf("Authentication scheme %s failed. Connection closed.", $zh->auth_h->auth[0]->scheme), __LINE__, __FUNCTION__);
    }
    else {
        LOG_INFO(sprintf("Authentication scheme %s succeeded", $zh->auth_h->auth[0]->scheme), __LINE__, __FUNCTION__);
    }
    $a_tmp = &$a_list;
    // chain call user's completion function
    /*
    while (a_tmp->completion != NULL) {
        auth_completion = a_tmp->completion;
        auth_completion(rc, a_tmp->auth_data);
        a_tmp = a_tmp->next;
        if (a_tmp == NULL)
            break;
    }
    //*/
    foreach($a_tmp as $a_tmp_val){
        $auth_completion = &$a_tmp->completion;
        //这里有个问题需要解决：$auth_completion应该是个callback，现在则是个类，下面设置一个断言来进行调试
        //定义了__invoke()魔术方法，来实现类的函数式调用
        //assert('$auth_completion($rc, $a_tmp_val->auth_data);');
        $auth_completion($rc, $a_tmp_val->auth_data);
    }
    free_auth_completion($a_list);
}

function send_info_packet(zhandle_t &$zh, auth_info &$auth) : int
{
    /*
    struct oarchive *oa;
    struct RequestHeader h = { STRUCT_INITIALIZER(xid , AUTH_XID), STRUCT_INITIALIZER(type , ZOO_SETAUTH_OP)};
    struct AuthPacket req;
    int rc;
    oa = create_buffer_oarchive();
    rc = serialize_RequestHeader(oa, "header", &h);
    req.type=0;   // ignored by the server
    req.scheme = auth->scheme;
    req.auth = auth->auth;
    rc = rc < 0 ? rc : serialize_AuthPacket(oa, "req", &req);
    /* add this buffer to the head of the send queue *
    rc = rc < 0 ? rc : queue_front_buffer_bytes(&zh->to_send, get_buffer(oa),
            get_buffer_len(oa));
    /* We queued the buffer, so don't free it *
    close_buffer_oarchive(&oa, 0);

    return rc;
    //*/
    $oa = new oarchive();
    $h = new RequestHeader(AUTH_XID, ZOO_SETAUTH_OP);
    $req = new AuthPacket();
    $oa = create_buffer_oarchive();
    $rc = Serialize::serialize_RequestHeader($oa, "header", $h);
    $req->type = 0;
    $req->scheme = $auth->scheme;
    $req->auth = $auth->auth;
    $rc = $rc < 0 ? $rc : Serialize::serialize_AuthPacket($oa, "req", $req);
    /* add this buffer to the head of the send queue */
    $rc = $rc < 0 ? $rc : queue_front_buffer_bytes($zh->to_send, get_buffer($oa), get_buffer_len($oa));
    /* We queued the buffer, so don't free it */
    close_buffer_oarchive($oa, 0);

    return $rc;
}

/** send all auths, not just the last one **/
function send_auth_info(zhandle_t &$zh) : int
{
    /*
    int rc = 0;
    auth_info *auth = NULL;

    zoo_lock_auth(zh);
    auth = zh->auth_h.auth;
    if (auth == NULL) {
        zoo_unlock_auth(zh);
        return ZOK;
    }
    while (auth != NULL) {
        rc = send_info_packet(zh, auth);
        auth = auth->next;
    }
    zoo_unlock_auth(zh);
    LOG_DEBUG(("Sending all auth info request to %s", format_current_endpoint_info(zh)));
    return (rc <0) ? ZMARSHALLINGERROR:ZOK;
    //*/
    $auth = new auth_info();
    zoo_lock_auth($zh);
    $auth = $zh->auth_h->auth;
    if(!$auth){
        zoo_unlock_auth($zh);
        return ZOK;
    }
    foreach($auth as $oneauth){
        $rc = send_info_packet($zh, $oneauth);
    }
    zoo_unlock_auth($zh);
    LOG_DEBUG(sprintf("Sending all auth info request to %s", format_current_endpoint_info($zh)), __LINE__, __FUNCTION__);
    return ($rc < 0) ? ZMARSHALLINGERROR : ZOK;
}

function send_last_auth_info(zhandle_t &$zh) : int
{
    /*    
    int rc = 0;
    auth_info *auth = NULL;

    zoo_lock_auth(zh);
    auth = get_last_auth(&zh->auth_h);
    if(auth==NULL) {
      zoo_unlock_auth(zh);
      return ZOK; // there is nothing to send
    }
    rc = send_info_packet(zh, auth);
    zoo_unlock_auth(zh);
    LOG_DEBUG(("Sending auth info request to %s",format_current_endpoint_info(zh)));
    return (rc < 0)?ZMARSHALLINGERROR:ZOK;
    //*/
    $rc = 0;
    $auth = new auth_info();
    zoo_lock_auth($zh);
    $auth = get_last_auth($zh->auth_h);
    if(!$auth) {
      zoo_unlock_auth($zh);
      return ZOK; // there is nothing to send
    }
    $rc = send_info_packet($zh, $auth);
    zoo_unlock_auth($zh);
    LOG_DEBUG(sprintf("Sending auth info request to %s",format_current_endpoint_info(zh)), __LINE__, __FUNCTION__);
    return ($rc < 0) ? ZMARSHALLINGERROR : ZOK;
}

function check_events(zhandle_t &$zh, int $events) : int
{
    if ($zh->fd == -1)
        return ZINVALIDSTATE;
    if (($events & ZOOKEEPER_WRITE) && ($zh->state == ZOO_CONNECTING_STATE)) {
        $rc = $error = 0;
//        socklen_t $len = sizeof(error);
//        rc = getsockopt(zh->fd, SOL_SOCKET, SO_ERROR, &error, &len);
        $rc = socket_getopt($zh->fd, SOL_SOCKET, SO_ERROR);
        $error = socket_last_error();
        /* the description in section 16.4 "Non-blocking connect"
         * in UNIX Network Programming vol 1, 3rd edition, points out
         * that sometimes the error is in errno and sometimes in error */
        if (!$rc || !$error) {
            if ($rc == 0)
                $errno = $error;
            return handle_socket_error_msg($zh, __LINE__,ZCONNECTIONLOSS,
                "server refused to accept the client");
        }
        if(($rc = prime_connection($zh))!=0)
            return $rc;
        LOG_INFO(sprintf("initiated connection to server [%s]",
                format_endpoint_info($zh->addrs[$zh->connect_index])), __LINE__, __FUNCTION__);
        return ZOK;
    }
    if ($zh->to_send->head && ($events & ZOOKEEPER_WRITE)) {
        /* make the flush call non-blocking by specifying a 0 timeout */
        $rc = flush_send_queue($zh,0);
        if ($rc < 0)
            return handle_socket_error_msg($zh, __LINE__ ,ZCONNECTIONLOSS,
                "failed while flushing send queue");
    }
    if ($events & ZOOKEEPER_READ) {
        $rc = 0;
        if ($zh->input_buffer == 0) {
            $zh->input_buffer = allocate_buffer(0,0);
        }

        $rc = recv_buffer($zh->fd, $zh->input_buffer);
        if ($rc < 0) {
            return handle_socket_error_msg($zh, __LINE__,ZCONNECTIONLOSS,
                "failed while receiving a server response");
        }
        if ($rc > 0) {
            gettimeofday($zh->last_recv, 0);
            if ($zh->input_buffer != $zh->primer_buffer) {
                queue_buffer($zh->to_process, $zh->input_buffer, 0);
            } else  {
//                int64_t oldid,newid;
                $oldid = $newid = 0;
                //deserialize
                deserialize_prime_response($zh->primer_storage, $zh->primer_buffer->buffer);
                /* We are processing the primer_buffer, so we need to finish
                 * the connection handshake */
                $oldid = $zh->client_id->client_id;
                $newid = $zh->primer_storage->sessionId;
                if ($oldid != 0 && $oldid != $newid) {
                    $zh->state = ZOO_EXPIRED_SESSION_STATE;
                    $errno = ESTALE;
                    return handle_socket_error_msg(zh,__LINE__,ZSESSIONEXPIRED,
                            "sessionId=%#llx has expired.",oldid);
                } else {
                    $zh->recv_timeout = $zh->primer_storage->timeOut;
                    $zh->client_id->client_id = $newid;
                    $zh->client_id->passwd = $zh->primer_storage->passwd;
//                    memcpy(zh->client_id.passwd, &zh->primer_storage.passwd, sizeof(zh->client_id.passwd));
                    $zh->state = ZOO_CONNECTED_STATE;
                    LOG_INFO(sprintf("session establishment complete on server [%s], sessionId=%#llx, negotiated timeout=%d",
                              format_endpoint_info($zh->addrs[$zh->connect_index]),
                              $newid, $zh->recv_timeout), __LINE__, __FUNCTION__);
                    /* we want the auth to be sent for, but since both call push to front
                       we need to call send_watch_set first */
                    send_set_watches($zh);
                    /* send the authentication packet now */
                    send_auth_info($zh);
                    LOG_DEBUG(sprintf("Calling a watcher for a ZOO_SESSION_EVENT and the state=ZOO_CONNECTED_STATE"), __LINE__, __FUNCTION__);
                    $zh->input_buffer = 0; // just in case the watcher calls zookeeper_process() again
                    PROCESS_SESSION_EVENT($zh, ZOO_CONNECTED_STATE);
                }
            }
            $zh->input_buffer = 0;
        } else {
            // zookeeper_process was called but there was nothing to read
            // from the socket
            return ZNOTHING;
        }
    }
    return ZOK;
}

function inc_ref_counter(zhandle_t &$zh, int $i) : int
{
    $zh->ref_counter += ($i < 0 ? -1 : ($i > 0 ? 1 : 0));
    return $zh->ref_counter;
}

function api_prolog(zhandle_t &$zh)
{
    inc_ref_counter($zh,1);
}

function calculate_interval(timeval &$start, timeval &$end) : int
{
    $interval = 0;
    $i = $end;
    $i->tv_sec -= $start->tv_sec;
    $i->tv_usec -= $start->tv_usec;
    $interval = $i->tv_sec * 1000 + ($i->tv_usec/1000);
    return $interval;
}

function process_sync_completion(completion_list_t &$cptr, sync_completion &$sc, iarchive &$ia, zhandle_t &$zh)
{
    LOG_DEBUG(sprintf("Processing sync_completion with type=%d xid=%#x rc=%d", $cptr->c->type, $cptr->xid, $sc->rc));

    switch($cptr->c->type) {
    case COMPLETION_DATA: 
        if ($sc->rc == 0) {
            /*
            struct GetDataResponse res;
            int len;
            deserialize_GetDataResponse(ia, "reply", &res);
            if (res.data.len <= sc->u.data.buff_len) {
                len = res.data.len;
            } else {
                len = sc->u.data.buff_len;
            }
            sc->u.data.buff_len = len;
            // check if len is negative
            // just of NULL which is -1 int
            if (len == -1) {
                sc->u.data.buffer = NULL;
            } else {
                memcpy(sc->u.data.buffer, res.data.buff, len);
            }
            sc->u.data.stat = res.stat;
            deallocate_GetDataResponse(&res);
            //*/
            $res = new GetDataResponse();
            $len = 0;
            Serialize::deserialize_GetDataResponse($ia, "reply", $res);
            if ($res->data->len <= $sc->u->data->buff_len) {
                $len = $res->data->len;
            } else {
                $len = $sc->u->data->buff_len;
            }
            $sc->u->data->buff_len = $len;
            // check if len is negative
            // just of NULL which is -1 int
            if ($len == -1) {
                $sc->u->data->buff_len = NULL;
            } else {
                $sc->u->data->buff_len = substr($res->data->buff, 0, $len);
            }
            $sc->u->data->stat = $res->stat;
            Serialize::deallocate_GetDataResponse($res);
        }
        break;
    case COMPLETION_STAT:
        if ($sc->rc == 0) {
            /*
            struct SetDataResponse res;
            deserialize_SetDataResponse(ia, "reply", &res);
            sc->u.stat = res.stat;
            deallocate_SetDataResponse(&res);
            //*/
            $res = new SetDataResponse();
            Serialize::deserialize_SetDataResponse($ia, "reply", $res);
            $sc->u->stat = $res->stat;
            Serialize::deallocate_SetDataResponse($res);
        }
        break;
    case COMPLETION_STRINGLIST:
        if ($sc->rc==0) {
            /*
            struct GetChildrenResponse res;
            deserialize_GetChildrenResponse(ia, "reply", &res);
            sc->u.strs2 = res.children;
            //*/
            /* We don't deallocate since we are passing it back */
            // deallocate_GetChildrenResponse(&res);
            
            $res = new GetChildrenResponse();
            Serialize::deserialize_GetChildrenResponse($ia, "reply", $res);
            $sc->u->strs2 = $res->children;
        }
        break;
    case COMPLETION_STRINGLIST_STAT:
        if ($sc->rc==0) {
            /*
            struct GetChildren2Response res;
            deserialize_GetChildren2Response(ia, "reply", &res);
            sc->u.strs_stat.strs2 = res.children;
            sc->u.strs_stat.stat2 = res.stat;
            //*/
            /* We don't deallocate since we are passing it back */
            // deallocate_GetChildren2Response(&res);
            
            $res = new GetChildren2Response();
            Serialize::deserialize_GetChildren2Response($ia, "reply", $res);
            $sc->u->strs_stat->strs2 = $res->children;
            $sc->u->strs_stat->stat2 = $res->stat;
        }
        break;
    case COMPLETION_STRING:
        if ($sc->rc==0) {
            /*
            struct CreateResponse res;
            int len;
            const char * client_path;
            deserialize_CreateResponse(ia, "reply", &res);
            //ZOOKEEPER-1027
            client_path = sub_string(zh, res.path); 
            len = strlen(client_path) + 1;if (len > sc->u.str.str_len) {
                len = sc->u.str.str_len;
            }
            if (len > 0) {
                memcpy(sc->u.str.str, client_path, len - 1);
                sc->u.str.str[len - 1] = '\0';
            }
            free_duplicate_path(client_path, res.path);
            deallocate_CreateResponse(&res);
            //*/
            
            $res = new CreateResponse();
            $len = 0;
            $client_path = '';
            Serialize::deserialize_CreateResponse($ia, "reply", $res);
            //ZOOKEEPER-1027
            $client_path = sub_string($zh, $res->path); 
            $len = strlen($client_path) + 1;
            if ($len > $sc->u->str->str_len) {
                $len = $sc->u->str->str_len;
            }
            if ($len > 0) {
                $sc->u->str->str = substr($client_path, $len - 1);
                $sc->u->str->str .= '\0';
            }
            free_duplicate_path($client_path, $res->path);
            Serialize::deallocate_CreateResponse($res);
        }
        break;
    case COMPLETION_ACLLIST:
        if ($sc->rc==0) {
            /*
            struct GetACLResponse res;
            deserialize_GetACLResponse(ia, "reply", &res);
            sc->u.acl.acl = res.acl;
            sc->u.acl.stat = res.stat;
            //*/
            /* We don't deallocate since we are passing it back */
            //deallocate_GetACLResponse(&res);
            
            $res = new GetACLResponse();
            Serialize::deserialize_GetACLResponse($ia, "reply", $res);
            $sc->u->acl->acl = $res->acl;
            $sc->u->acl->stat = $res->stat;
        }
        break;
    case COMPLETION_VOID:
        break;
    case COMPLETION_MULTI:
        $sc->rc = deserialize_multi($cptr->xid, $cptr, $ia);
        break;
    default:
        LOG_DEBUG(sprintf("Unsupported completion type=%d", $cptr->c->type));
        break;
    }
}

function zookeeper_process(zhandle_t &$zh, int $events) : int
{
//    buffer_list_t *bptr;
    $bptr = new buffer_list_t();
//    int rc;
    $rc = 0;

    if (!$zh)
        return ZBADARGUMENTS;
    if (is_unrecoverable($zh))
        return ZINVALIDSTATE;
    api_prolog($zh);
//    IF_DEBUG(checkResponseLatency(zh));
    $rc = check_events($zh, $events);
    if ($rc!=ZOK)
        return api_epilog($zh, $rc);

    IF_DEBUG(isSocketReadable($zh));

    while ($rc >= 0 && ($bptr = dequeue_buffer($zh->to_process))) {
        /*
        struct ReplyHeader hdr;
        struct iarchive *ia = create_buffer_iarchive(bptr->buffer, bptr->curr_offset);
        deserialize_ReplyHeader(ia, "hdr", &hdr);
        //*/
        $hdr = new ReplyHeader();
        $ia = new iarchive();
        $ia = create_buffer_iarchive($bptr->buffer, $bptr->curr_offset);
        Serialize::deserialize_ReplyHeader($ia, "hdr", $hdr);
        if ($hdr->zxid > 0) {
            $zh->last_zxid = $hdr->zxid;
        } else {
            // fprintf(stderr, "Got %#x for %#x\n", hdr.zxid, hdr.xid);
        }

        if ($hdr->xid == PING_XID) {
            // Ping replies can arrive out-of-order
            /*
            int elapsed = 0;
            struct timeval now;
            gettimeofday(&now, 0);
            elapsed = calculate_interval(&zh->last_ping, &now);
            LOG_DEBUG(("Got ping response in %d ms", elapsed));
            free_buffer(bptr);
            //*/
            $elapsed = 0;
            $now = new timeval();
            gettimeofday($now, 0);
            $elapsed = calculate_interval($zh->last_ping, $now);
            LOG_DEBUG(sprintf("Got ping response in %d ms", $elapsed), __LINE__, __FUNCTION__);
            free_buffer($bptr);
        } else if ($hdr->xid == WATCHER_EVENT_XID) {
            /*
            struct WatcherEvent evt;
            int type = 0;
            char *path = NULL;
            completion_list_t *c = NULL;
            //*/
//            struct WatcherEvent evt;
            $evt = new WatcherEvent();
            $type = 0;
            $path = '';
//            completion_list_t *c = NULL;
            $c = new completion_list_t();
            LOG_DEBUG(sprintf("Processing WATCHER_EVENT"), __LINE__, __FUNCTION__);

            Serialize::deserialize_WatcherEvent($ia, "event", $evt);
            $type = $evt->type;
            $path = $evt->path;
            /* We are doing a notification, so there is no pending request */
            $c = create_completion_entry(WATCHER_EVENT_XID,-1,0,0,0,0);
            $c->buffer = $bptr;
            $c->c->watcher_result = collectWatchers($zh, $type, $path);

            // We cannot free until now, otherwise path will become invalid
            Serialize::deallocate_WatcherEvent($evt);
            queue_completion($zh->completions_to_process, $c, 0);
        } else if ($hdr->xid == SET_WATCHES_XID) {
            LOG_DEBUG(sprintf("Processing SET_WATCHES"), __LINE__, __FUNCTION__);
            free_buffer($bptr);
        } else if ($hdr->xid == AUTH_XID){
            LOG_DEBUG(sprintf("Processing AUTH_XID"), __LINE__, __FUNCTION__);

            /* special handling for the AUTH response as it may come back
             * out-of-band */
            auth_completion_func($hdr->err, $zh);
            free_buffer($bptr);
            /* authentication completion may change the connection state to
             * unrecoverable */
            if(is_unrecoverable($zh)){
                handle_error($zh, ZAUTHFAILED);
                close_buffer_iarchive($ia);
                return api_epilog($zh, ZAUTHFAILED);
            }
        } else {
            $rc = $hdr->err;
            /* Find the request corresponding to the response */
            $cptr = dequeue_completion($zh->sent_requests);

            /* [ZOOKEEPER-804] Don't assert if zookeeper_close has been called. */
            if ($zh->close_requested == 1 && $cptr == NULL) {
                LOG_DEBUG(sprintf("Completion queue has been cleared by zookeeper_close()"), __LINE__, __FUNCTION__);
                close_buffer_iarchive($ia);
                free_buffer($bptr);
                return api_epilog($zh,ZINVALIDSTATE);
            }
            assert($cptr);
            /* The requests are going to come back in order */
            if ($cptr->xid != $hdr->xid) {
                LOG_DEBUG(sprintf("Processing unexpected or out-of-order response!"), __LINE__, __FUNCTION__);

                // received unexpected (or out-of-order) response
                close_buffer_iarchive($ia);
                free_buffer($bptr);
                // put the completion back on the queue (so it gets properly
                // signaled and deallocated) and disconnect from the server
                queue_completion($zh->sent_requests,$cptr,1);
                return api_epilog($zh,
                                  handle_socket_error_msg($zh, __LINE__,ZRUNTIMEINCONSISTENCY,
                                  "unexpected server response: expected %#x, but received %#x",
                                  $hdr->xid,$cptr->xid));
            }

            activateWatcher($zh, $cptr->watcher, $rc);

            if ($cptr->c->void_result != SYNCHRONOUS_MARKER) {
                LOG_DEBUG(sprintf("Queueing asynchronous response"));
                $cptr->buffer = $bptr;
                queue_completion($zh->completions_to_process, $cptr, 0);
            } else {
                /*
                struct sync_completion *sc = (struct sync_completion*)cptr->data;
                sc->rc = rc;
                struct sync_completion
                        *sc = (struct sync_completion*)cptr->data;
                sc->rc = rc;
                process_sync_completion(cptr, sc, ia, zh); 
                
                notify_sync_completion(sc);
                free_buffer(bptr);
                zh->outstanding_sync--;
                destroy_completion_entry(cptr);
                //*/
                
                $sc = new sync_completion();
                $sc = $cptr->data;
                $sc->rc = $rc;
                process_sync_completion($cptr, $sc, $ia, $zh); 
                
                notify_sync_completion($sc);
                free_buffer($bptr);
                $zh->outstanding_sync--;
                destroy_completion_entry($cptr);
            }
        }

        close_buffer_iarchive($ia);

    }
    if (process_async($zh->outstanding_sync)) {
        process_completions($zh);
    }
    return api_epilog($zh,ZOK);
}

function zoo_state(zhandle_t &$zh) : int
{
    if($zh != 0)
        return $zh->state;
    return 0;
}

function create_watcher_registration($path, $checker, watcher_fn $watcher, $ctx) : watcher_registration_t
{
    /*
    watcher_registration_t* wo;
    if(watcher==0)
        return 0;
    wo=calloc(1,sizeof(watcher_registration_t));
    wo->path=strdup(path);
    wo->watcher=watcher;
    wo->context=ctx;
    wo->checker=checker;
    return wo;
    //*/
    $wo = new watcher_registration_t();
    if(!$watcher)
        return 0;
    $wo->path = $path;
    $wo->watcher = $watcher;
    $wo->context = $ctx;
    $wo->checker = $checker;
    return $wo;
}

function destroy_watcher_registration(watcher_registration_t &$wo)
{
    /*
    if(wo!=0){
        free((void*)wo->path);
        free(wo);
    }
    //*/
    if($wo){
        $wo->path = null;
        $wo = null;
    }
}

function create_completion_entry(int $xid, int $completion_type,
        &$dc, &$data, watcher_registration_t &$wo, completion_head_t &$clist) : completion_list_t
{
//    completion_list_t *c = calloc(1,sizeof(completion_list_t));
    $c = new completion_list_t();
    $c->c = new completion_t();
    if (!$c) {
        LOG_ERROR(sprintf("out of memory"));
        return 0;
    }
    $c->c->type = $completion_type;
    $c->data = $data;
    switch($c->c->type) {
    case COMPLETION_VOID:
        $c->c->void_result = $dc; //void_completion_t
        break;
    case COMPLETION_STRING:
        $c->c->string_result = $dc; //string_completion_t
        break;
    case COMPLETION_DATA:
        $c->c->data_result = $dc; //data_completion_t
        break;
    case COMPLETION_STAT:
        $c->c->stat_result = $dc; //stat_completion_t
        break;
    case COMPLETION_STRINGLIST:
        $c->c->strings_result = $dc; //strings_completion_t
        break;
    case COMPLETION_STRINGLIST_STAT:
        $c->c->strings_stat_result = $dc; //strings_stat_completion_t
        break;
    case COMPLETION_ACLLIST:
        $c->c->acl_result = $dc; //acl_completion_t
        break;
    case COMPLETION_MULTI:
        assert($clist);
        $c->c->void_result = $dc; //void_completion_t
        $c->c->clist = &$clist;
        break;
    }
    $c->xid = $xid;
    $c->watcher = $wo;
    
    return $c;
}

function destroy_completion_entry(completion_list_t &$c)
{
    if($c){
        destroy_watcher_registration($c->watcher);
        if($c->buffer!=0)
            free_buffer($c->buffer);
        $c = null;
    }
}

function queue_completion_nolock(completion_head_t &$list, completion_list_t &$c, int $add_to_front)
{
    $c->next = 0;
    /* appending a new entry to the back of the list */
    if ($list->last) {
        assert($list->head);
        // List is not empty
        if (!$add_to_front) {
            $list->last->next = &$c;
            $list->last = &$c;
        } else {
            $c->next = &$list->head;
            $list->head = &$c;
        }
    } else {
        // List is empty
        assert(!$list->head);
        $list->head = &$c;
        $list->last = &$c;
    }
}

function queue_completion(completion_head_t &$list, completion_list_t &$c, int $add_to_front)
{
    lock_completion_list($list);
    queue_completion_nolock($list, $c, $add_to_front);
    unlock_completion_list($list);
}

function add_completion(zhandle_t &$zh, int $xid, int $completion_type,
        &$dc, &$data, int $add_to_front,
        watcher_registration_t $wo, completion_head_t $clist) : int
{
    /*
    completion_list_t *c = create_completion_entry(xid, completion_type, dc,
            data, wo, clist);
    int rc = 0;
    if (!c)
        return ZSYSTEMERROR;
    lock_completion_list(&zh->sent_requests);
    if (zh->close_requested != 1) {
        queue_completion_nolock(&zh->sent_requests, c, add_to_front);
        if (dc == SYNCHRONOUS_MARKER) {
            zh->outstanding_sync++;
        }
        rc = ZOK;
    } else {
        free(c);
        rc = ZINVALIDSTATE;
    }
    unlock_completion_list(&zh->sent_requests);
    return rc;
    //*/
    $c = new completion_list_t();
    $c = create_completion_entry($xid, $completion_type, $dc,
            $data, $wo, $clist);
    $rc = 0;
    if (!$c)
        return ZSYSTEMERROR;
    lock_completion_list($zh->sent_requests);
    if ($zh->close_requested != 1) {
        queue_completion_nolock($zh->sent_requests, $c, $add_to_front);
        if ($dc == SYNCHRONOUS_MARKER) {
            $zh->outstanding_sync++;
        }
        $rc = ZOK;
    } else {
        $c = null;
        $rc = ZINVALIDSTATE;
    }
    unlock_completion_list($zh->sent_requests);
    return $rc;
}

function add_data_completion(zhandle_t &$zh, int $xid, data_completion_t $dc, &$data, watcher_registration_t $wo) : int
{
    $clist = new completion_head_t();
    return add_completion($zh, $xid, COMPLETION_DATA, $dc, $data, 0, $wo, $clist);
}

function add_stat_completion(zhandle_t &$zh, int $xid, stat_completion_t $dc, $data, watcher_registration_t $wo) : int
{
    $clist = new completion_head_t();
    return add_completion($zh, $xid, COMPLETION_STAT, $dc, $data, 0, $wo, $clist);
}

function add_strings_completion(zhandle_t &$zh, int $xid, strings_completion_t $dc, $data, watcher_registration_t $wo) : int
{
    $clist = new completion_head_t();
    return add_completion($zh, $xid, COMPLETION_STRINGLIST, $dc, $data, 0, $wo, $clist);
}

function add_strings_stat_completion(zhandle_t &$zh, int $xid, strings_stat_completion_t $dc, &$data,watcher_registration_t $wo) : int
{
    $clist = new completion_head_t();
    return add_completion($zh, $xid, COMPLETION_STRINGLIST_STAT, $dc, $data, 0, $wo, $clist);
}

function add_acl_completion(zhandle_t &$zh, int $xid, acl_completion_t $dc, $data) : int
{
    $clist = new completion_head_t();
    return add_completion($zh, $xid, COMPLETION_ACLLIST, $dc, $data, 0, 0, $clist);
}

function add_void_completion(zhandle_t &$zh, int $xid, void_completion_t $dc, $data) : int
{
    $clist = new completion_head_t();
    return add_completion($zh, $xid, COMPLETION_VOID, $dc, $data, 0, 0, $clist);
}

function add_string_completion(zhandle_t &$zh, int $xid, string_completion_t $dc, $data) : int
{
    $clist = new completion_head_t();
    $wo = new watcher_registration_t();
    return add_completion($zh, $xid, COMPLETION_STRING, $dc, $data, 0, $wo, $clist);
}

function add_multi_completion(zhandle_t &$zh, int $xid, void_completion_t $dc, &$data, completion_head_t &$clist) : int
{
    return add_completion($zh, $xid, COMPLETION_MULTI, $dc, $data, 0,0, $clist);
}

function zookeeper_close(zhandle_t &$zh) : int
{
    $rc = ZOK;
    if (!$zh)
        return ZBADARGUMENTS;

    $zh->close_requested = 1;
    if (inc_ref_counter($zh,1)>1) {
        /* We have incremented the ref counter to prevent the
         * completions from calling zookeeper_close before we have
         * completed the adaptor_finish call below. */

    /* Signal any syncronous completions before joining the threads */
        enter_critical($zh);
        free_completions($zh,1,ZCLOSING);
        leave_critical($zh);

        adaptor_finish($zh);
        /* Now we can allow the handle to be cleaned up, if the completion
         * threads finished during the adaptor_finish call. */
        api_epilog($zh, 0);
        return ZOK;
    }
    /* No need to decrement the counter since we're just going to
     * destroy the handle later. */
    if($zh->state==ZOO_CONNECTED_STATE){
//        struct oarchive *oa;
//        struct RequestHeader h = { STRUCT_INITIALIZER (xid , get_xid()), STRUCT_INITIALIZER (type , ZOO_CLOSE_OP)};
        $oa = new oarchive();
        $h = new ReplyHeader(get_xid(), ZOO_CLOSE_OP);
        LOG_INFO(sprintf("Closing zookeeper sessionId=%#llx to [%s]\n",
                $zh->client_id->client_id, format_current_endpoint_info($zh)));
        $oa = create_buffer_oarchive();
        $rc = serialize::serialize_RequestHeader($oa, "header", $h);
        $rc = $rc < 0 ? $rc : queue_buffer_bytes($zh->to_send, get_buffer($oa),
                get_buffer_len($oa));
        /* We queued the buffer, so don't free it */
        close_buffer_oarchive($oa, 0);
        if ($rc < 0) {
            $rc = ZMARSHALLINGERROR;
            goto finish;
        }

        /* make sure the close request is sent; we set timeout to an arbitrary
         * (but reasonable) number of milliseconds since we want the call to block*/
        $rc = adaptor_send_queue($zh, 3000);
    }else{
        LOG_INFO(sprintf("Freeing zookeeper resources for sessionId=%#llx\n",
                $zh->client_id->client_id));
        $rc = ZOK;
    }

finish:
    destroy($zh);
    adaptor_destroy($zh);
    $zh = null;
    return $rc;
}

function isValidPath(&$path, int $flags) : int
{
    $len = 0;
    $lastc = '/';
    $c = '';
    $i = 0;

  if (!$path)
    return 0;
  $len = strlen($path);
  if ($len == 0)
    return 0;
  if (substr($path, 0, 1) != '/')
    return 0;
  if ($len == 1) // done checking - it's the root
    return 1;
  if (substr($path, -1) == '/' && !($flags & ZOO_SEQUENCE))
    return 0;

  $i = 1;
  for (; $i < $len; $lastc = substr($path, $i, 1), $i++) {
    $c = substr($path, $i, 1);

    if (!$c) {
      return 0;
    } else if ($c == '/' && $lastc == '/') {
      return 0;
    } else if ($c == '.' && $lastc == '.') {
      if (substr($path, $i - 2, 1) == '/' && ((($i + 1 == $len) && !($flags & ZOO_SEQUENCE)) || substr($path, $i + 1, 1) == '/')) {
        return 0;
      }
    } else if ($c == '.') {
      if ((substr($path, $i - 1, 1) == '/') && ((($i + 1 == $len) && !($flags & ZOO_SEQUENCE)) || substr($path, $i + 1, 1) == '/')) {
        return 0;
      }
    } else if (ord($c) > 0 && ord($c) < 31) {
      return 0;
    }
  }

  return 1;
}

/**
  prepend the chroot path if available else return the path
*/
function prepend_string(zhandle_t &$zh, string &$client_path) : string
{
//    char *ret_str;
    $ret_str = '';
    if ($zh == NULL || $zh->chroot == NULL)
        return $client_path;
    // handle the chroot itself, client_path = "/"
    if (strlen($client_path) == 1) {
        return $zh->chroot;
    }
    $ret_str = $zh->chroot;
    return $ret_str . $client_path;
}

/*---------------------------------------------------------------------------*
 * REQUEST INIT HELPERS
 *---------------------------------------------------------------------------*/
/* Common Request init helper functions to reduce code duplication */
function Request_path_init(zhandle_t &$zh, int $flags, string &$path_out, &$path) : int
{
    assert($path_out);
    
    $path_out = prepend_string($zh, $path);
    if ($zh == NULL || !isValidPath($path_out, $flags)) {
        free_duplicate_path($path_out, $path);
        return ZBADARGUMENTS;
    }
    if (is_unrecoverable($zh)) {
        free_duplicate_path($path_out, $path);
        return ZINVALIDSTATE;
    }

    return ZOK;
}

function Request_path_watch_init(zhandle_t &$zh, int $flags, string &$path_out, &$path, int &$watch_out, int $watch) : int
{
    $rc = Request_path_init($zh, $flags, $path_out, $path);
    if ($rc != ZOK) {
        return $rc;
    }
    $watch_out = $watch;
    return ZOK;
}

/*---------------------------------------------------------------------------*
 * ASYNC API
 *---------------------------------------------------------------------------*/
function zoo_aget(zhandle_t &$zh, string &$path, int $watch, data_completion_t $dc, &$data) : int
{
    return zoo_awget($zh, $path, $watch ? $zh->watcher : 0, $zh->context, $dc, $data);
}

function zoo_awget(zhandle_t &$zh, string &$path, watcher_fn $watcher, &$watcherCtx, data_completion_t $dc, &$data) : int
{
    /*
    struct oarchive *oa;
    char *server_path = prepend_string(zh, path);
    struct RequestHeader h = { STRUCT_INITIALIZER (xid , get_xid()), STRUCT_INITIALIZER (type ,ZOO_GETDATA_OP)};
    struct GetDataRequest req =  { (char*)server_path, watcher!=0 };
    int rc;
    //*/
    $oa = new oarchive();
    $server_path = prepend_string($zh, $path);
    $h = new RequestHeader(get_xid(), ZOO_GETDATA_OP);
    $req = new GetDataRequest($server_path, $watcher != 0);
    $rc = 0;
    if ($zh==0 || !isValidPath($server_path, 0)) {
        free_duplicate_path($server_path, $path);
        return ZBADARGUMENTS;
    }
    if (is_unrecoverable($zh)) {
        free_duplicate_path($server_path, $path);
        return ZINVALIDSTATE;
    }
    /*
    oa=create_buffer_oarchive();
    rc = serialize_RequestHeader(oa, "header", &h);
    rc = rc < 0 ? rc : serialize_GetDataRequest(oa, "req", &req);
    enter_critical(zh);
    rc = rc < 0 ? rc : add_data_completion(zh, h.xid, dc, data,
        create_watcher_registration(server_path,data_result_checker,watcher,watcherCtx));
    rc = rc < 0 ? rc : queue_buffer_bytes(&zh->to_send, get_buffer(oa),
            get_buffer_len(oa));
    leave_critical(zh);
    free_duplicate_path(server_path, path);
    //*/
    $oa = create_buffer_oarchive();
    $rc = Serialize::serialize_RequestHeader($oa, "header", $h);
    $rc = $rc < 0 ? $rc : Serialize::serialize_GetDataRequest($oa, "req", $req);
    enter_critical($zh);
    $rc = $rc < 0 ? $rc : add_data_completion($zh, $h->xid, $dc, $data,
        create_watcher_registration($server_path, $data_result_checker, $watcher, $watcherCtx));
    $rc = $rc < 0 ? $rc : queue_buffer_bytes($zh->to_send, get_buffer($oa), get_buffer_len($oa));
    leave_critical($zh);
    free_duplicate_path($server_path, $path);
    /* We queued the buffer, so don't free it */
    close_buffer_oarchive($oa, 0);

    LOG_DEBUG(sprintf("Sending request xid=%#x for path [%s] to %s", $h->xid, $path, format_current_endpoint_info($zh)));
    /* make a best (non-blocking) effort to send the requests asap */
    adaptor_send_queue($zh, 0);
    return ($rc < 0) ? ZMARSHALLINGERROR : ZOK;
}


function SetDataRequest_init(zhandle_t &$zh, SetDataRequest &$req, string &$path, string &$buffer, int $buflen, int $version) : int
{
    $rc = 0;
    assert($req);
    $rc = Request_path_init($zh, 0, $req->path, $path);
    if ($rc != ZOK) {
        return $rc;
    }
    $req->data->buff = &$buffer;
    $req->data->len = $buflen;
    $req->version = $version;

    return ZOK;
}

function zoo_aset(zhandle_t &$zh, string $path, string $buffer, int $buflen, int $version, stat_completion_t $dc, string $data) : int
{
    /*
    struct oarchive *oa;
    struct RequestHeader h = { STRUCT_INITIALIZER(xid , get_xid()), STRUCT_INITIALIZER (type , ZOO_SETDATA_OP)};
    struct SetDataRequest req;
    int rc = SetDataRequest_init(zh, &req, path, buffer, buflen, version);
    //*/
    $oa = new oarchive();
    $h = new RequestHeader(get_xid(), ZOO_SETDATA_OP);
    $req = new SetDataRequest();
    $rc = SetDataRequest_init($zh, $req, $path, $buffer, $buflen, $version);
    if ($rc != ZOK) {
        return $rc;
    }
    $oa = create_buffer_oarchive();
    $rc = serialize::serialize_RequestHeader($oa, "header", $h);
    $rc = $rc < 0 ? $rc : serialize::serialize_SetDataRequest($oa, "req", $req);
    enter_critical($zh);
    $rc = $rc < 0 ? $rc : add_stat_completion($zh, $h->xid, $dc, $data, 0);
    $rc = $rc < 0 ? $rc : queue_buffer_bytes($zh->to_send, get_buffer($oa), get_buffer_len($oa));
    leave_critical($zh);
    free_duplicate_path($req->path, $path);
    /* We queued the buffer, so don't free it */
    close_buffer_oarchive($oa, 0);

    LOG_DEBUG(sprintf("Sending request xid=%#x for path [%s] to %s", $h->xid, $path, format_current_endpoint_info($zh)), __LINE__, __FUNCTION__);
    /* make a best (non-blocking) effort to send the requests asap */
    adaptor_send_queue($zh, 0);
    return ($rc < 0) ? ZMARSHALLINGERROR : ZOK;
}


function CreateRequest_init(zhandle_t &$zh, CreateRequest &$req, string $path, string $value,
        int $valuelen, ACL_vector &$acl_entries, int $flags) : int
{
    $rc = 0;
    assert($req);
    $rc = Request_path_init($zh, $flags, $req->path, $path);
    assert($req);
    if ($rc != ZOK) {
        return $rc;
    }
    $req->flags = $flags;
    $data = new Buffer($valuelen, $value);
    $req->data = $data;
    if (!$acl_entries) {
        $acl = new ACL();
        $acl_entries = new ACL_vector(0, $acl);
        $req->acl = &$acl_entries;
    } else {
        $req->acl = &$acl_entries;
    }

    return ZOK;
}

function zoo_acreate(zhandle_t &$zh, string $path, string $value, int $valuelen, ACL_vector &$acl_entries, int $flags,
        string_completion_t $completion, $data) : int
{
    /*
    struct oarchive *oa;
    struct RequestHeader h = { STRUCT_INITIALIZER (xid , get_xid()), STRUCT_INITIALIZER (type ,ZOO_CREATE_OP) };
    struct CreateRequest req;
    int rc = CreateRequest_init(zh, &req, 
            path, value, valuelen, acl_entries, flags);
    //*/
    $oa = new oarchive();
    $h = new RequestHeader(get_xid(), ZOO_CREATE_OP);
    $req = new CreateRequest();
    echo __FUNCTION__, " ", __LINE__, "\n";
    $rc = CreateRequest_init($zh, $req, $path, $value, $valuelen, $acl_entries, $flags);
    echo __FUNCTION__, " ", __LINE__, "\n";
    if ($rc != ZOK) {
        return $rc;
    }
    $oa = create_buffer_oarchive();
    $rc = Serialize::serialize_RequestHeader($oa, "header", $h);
    echo __FUNCTION__, " ", __LINE__, "\n";
    $rc = $rc < 0 ? $rc : Serialize::serialize_CreateRequest($oa, "req", $req);
    echo __FUNCTION__, " ", __LINE__, "\n";
    enter_critical($zh);
    echo __FUNCTION__, " ", __LINE__, "\n";
    $rc = $rc < 0 ? $rc : add_string_completion($zh, $h->xid, $completion, $data);
    echo __FUNCTION__, " ", __LINE__, "\n";
    $rc = $rc < 0 ? $rc : queue_buffer_bytes($zh->to_send, get_buffer($oa), get_buffer_len($oa));
    echo __FUNCTION__, " ", __LINE__, "\n";
    leave_critical($zh);
    echo __FUNCTION__, " ", __LINE__, "\n";
    free_duplicate_path($req->path, $path);
    echo __FUNCTION__, " ", __LINE__, "\n";
    /* We queued the buffer, so don't free it */
    close_buffer_oarchive($oa, 0);
    echo __FUNCTION__, " ", __LINE__, "\n";

    LOG_DEBUG(sprintf("Sending request xid=%#x for path [%s] to %s", $h->xid, $path, format_current_endpoint_info($zh)), __LINE__, __FUNCTION__);
    echo __FUNCTION__, " ", __LINE__, "\n";
    /* make a best (non-blocking) effort to send the requests asap */
    adaptor_send_queue($zh, 0);
    echo __FUNCTION__, " ", __LINE__, "\n";
    return ($rc < 0) ? ZMARSHALLINGERROR : ZOK;
}


function DeleteRequest_init(zhandle_t &$zh, DeleteRequest &$req, string &$path, int $version) : int
{
    $rc = Request_path_init($zh, 0, $req->path, $path);
    if ($rc != ZOK) {
        return $rc;
    }
    $req->version = $version;
    return ZOK;
}

function zoo_adelete(zhandle_t &$zh, string &$path, int $version, void_completion_t $completion, &$data) : int
{
    /*
    struct oarchive *oa;
    struct RequestHeader h = { STRUCT_INITIALIZER (xid , get_xid()), STRUCT_INITIALIZER (type , ZOO_DELETE_OP)};
    struct DeleteRequest req;
    int rc = DeleteRequest_init(zh, &req, path, version);
    //*/
    $oa = new oarchive();
    $h = new RequestHeader(get_xid(), ZOO_DELETE_OP);
    $req = new DeleteRequest();
    $rc = DeleteRequest_init($zh, $req, $path, $version);
    
    if ($rc != ZOK) {
        return $rc;
    }
    $oa = $create_buffer_oarchive();
    $rc = Serialize::serialize_RequestHeader($oa, "header", $h);
    $rc = $rc < 0 ? $rc : Serialize::serialize_DeleteRequest($oa, "req", $req);
    enter_critical($zh);
    $rc = $rc < 0 ? $rc : add_void_completion($zh, $h->xid, $completion, $data);
    $rc = $rc < 0 ? $rc : queue_buffer_bytes($zh->to_send, get_buffer($oa), get_buffer_len($oa));
    leave_critical($zh);
    free_duplicate_path($req->path, $path);
    /* We queued the buffer, so don't free it */
    close_buffer_oarchive($oa, 0);

    LOG_DEBUG(sprintf("Sending request xid=%#x for path [%s] to %s", $h->xid, $path, format_current_endpoint_info($zh)));
    /* make a best (non-blocking) effort to send the requests asap */
    adaptor_send_queue($zh, 0);
    return ($rc < 0) ? ZMARSHALLINGERROR : ZOK;
}

function zoo_aexists(zhandle_t &$zh, string &$path, int $watch, stat_completion_t $sc, &$data) : int
{
    return zoo_awexists($zh, $path, $watch ? $zh->watcher : 0, $zh->context, $sc, $data);
}

function zoo_awexists(zhandle_t &$zh, string &$path, watcher_fn $watcher, &$watcherCtx,
        stat_completion_t $completion, &$data) : int
{
    /*
    struct oarchive *oa;
    struct RequestHeader h = { STRUCT_INITIALIZER (xid ,get_xid()), STRUCT_INITIALIZER (type , ZOO_EXISTS_OP) };
    struct ExistsRequest req;
    int rc = Request_path_watch_init(zh, 0, &req.path, path, 
            &req.watch, watcher != NULL);
    //*/
    $oa = new oarchive();
    $h = new RequestHeader(get_xid(), ZOO_EXISTS_OP);
    $req = new ExistsRequest();
    $rc = Request_path_watch_init($zh, 0, $req->path, $path, $req->watch, $watcher != NULL);
    if ($rc != ZOK) {
        return $rc;
    }
    $oa = create_buffer_oarchive();
    $rc = Serialize::serialize_RequestHeader($oa, "header", $h);
    $rc = $rc < 0 ? $rc : Serialize::serialize_ExistsRequest($oa, "req", $req);
    enter_critical($zh);
    $rc = $rc < 0 ? $rc : add_stat_completion($zh, $h->xid, $completion, $data, create_watcher_registration($req->path, "exists_result_checker",
                $watcher,$watcherCtx));
    $rc = $rc < 0 ? rc : queue_buffer_bytes($zh->to_send, get_buffer($oa), get_buffer_len($oa));
    leave_critical($zh);
    free_duplicate_path($req->path, $path);
    /* We queued the buffer, so don't free it */
    close_buffer_oarchive($oa, 0);

    LOG_DEBUG(sprintf("Sending request xid=%#x for path [%s] to %s", $h->xid, $path, format_current_endpoint_info($zh)));
    /* make a best (non-blocking) effort to send the requests asap */
    adaptor_send_queue($zh, 0);
    return ($rc < 0) ? ZMARSHALLINGERROR : ZOK;
}

function zoo_awget_children_(zhandle_t &$zh, string &$path, watcher_fn $watcher, $watcherCtx, strings_completion_t $sc, $data) : int
{
    /*
    struct oarchive *oa;
    struct RequestHeader h = { STRUCT_INITIALIZER (xid , get_xid()), STRUCT_INITIALIZER (type , ZOO_GETCHILDREN_OP)};
    struct GetChildrenRequest req ;
    int rc = Request_path_watch_init(zh, 0, &req.path, path, &req.watch, watcher != NULL);
    //*/
    $oa = new oarchive();
    $h = new RequestHeader(get_xid(), ZOO_GETCHILDREN_OP);
    $req = new GetChildrenRequest();
    $rc = Request_path_watch_init($zh, 0, $req->path, $path, $req->watch, $watcher != NULL);
    
    if ($rc != ZOK) {
        return $rc;
    }
    $oa = create_buffer_oarchive();
    $rc = Serialize::serialize_RequestHeader($oa, "header", $h);
    $rc = $rc < 0 ? $rc : Serialize::serialize_GetChildrenRequest($oa, "req", $req);
    enter_critical($zh);
    $rc = $rc < 0 ? $rc : add_strings_completion($zh, $h->xid, $sc, $data,
            create_watcher_registration($req->path, "child_result_checker", $watcher, $watcherCtx));
    $rc = $rc < 0 ? $rc : queue_buffer_bytes($zh->to_send, get_buffer($oa), get_buffer_len($oa));
    leave_critical($zh);
    free_duplicate_path($req->path, $path);
    /* We queued the buffer, so don't free it */
    close_buffer_oarchive($oa, 0);

    LOG_DEBUG(sprintf("Sending request xid=%#x for path [%s] to %s", $h->xid, $path, format_current_endpoint_info($zh)), __LINE__, __FUNCTION__);
    /* make a best (non-blocking) effort to send the requests asap */
    adaptor_send_queue($zh, 0);
    return ($rc < 0) ? ZMARSHALLINGERROR : ZOK;
}

function zoo_aget_children(zhandle_t &$zh, string $path, watcher_fn $watcher, strings_completion_t $dc, $data) : int
{
    return zoo_awget_children_($zh, $path, $watcher ? $zh->watcher : 0, $zh->context, $dc, $data);
}

function zoo_awget_children(zhandle_t &$zh, string &$path, watcher_fn $watcher, &$watcherCtx, strings_completion_t $dc, &$data) : int
{
    return zoo_awget_children_($zh, $path, $watcher, $watcherCtx, $dc, $data);
}

function zoo_awget_children2_(zhandle_t &$zh, string &$path, watcher_fn $watcher, &$watcherCtx, strings_stat_completion_t $ssc, &$data) : int
{
    /* invariant: (sc == NULL) != (sc == NULL) */
    /*
    struct oarchive *oa;
    struct RequestHeader h = { STRUCT_INITIALIZER( xid, get_xid()), STRUCT_INITIALIZER (type ,ZOO_GETCHILDREN2_OP)};
    struct GetChildren2Request req ;
    int rc = Request_path_watch_init(zh, 0, &req.path, path, 
            &req.watch, watcher != NULL);
    //*/
    $oa = new oarchive();
    $h = new RequestHeader(get_xid(), ZOO_GETCHILDREN2_OP);
    $req = new GetChildren2Request();
    $rc = Request_path_watch_init($zh, 0, $req->path, $path, $req->watch, $watcher != NULL);
    
    if ($rc != ZOK) {
        return $rc;
    }
    $oa = create_buffer_oarchive();
    $rc = Serialize::serialize_RequestHeader($oa, "header", $h);
    $rc = $rc < 0 ? $rc : Serialize::serialize_GetChildren2Request($oa, "req", $req);
    enter_critical($zh);
    $rc = $rc < 0 ? $rc : add_strings_stat_completion($zh, $h->xid, $ssc, $data,
            create_watcher_registration($req->path, "child_result_checker", $watcher, $watcherCtx));
    $rc = $rc < 0 ? $rc : queue_buffer_bytes($zh->to_send, get_buffer($oa), get_buffer_len($oa));
    leave_critical($zh);
    free_duplicate_path($req->path, $path);
    /* We queued the buffer, so don't free it */
    close_buffer_oarchive($oa, 0);

    LOG_DEBUG(sprintf("Sending request xid=%#x for path [%s] to %s", $h->xid, $path,
            format_current_endpoint_info($zh)));
    /* make a best (non-blocking) effort to send the requests asap */
    adaptor_send_queue($zh, 0);
    return ($rc < 0) ? ZMARSHALLINGERROR : ZOK;
}

function zoo_aget_children2(zhandle_t &$zh, string &$path, int $watch, strings_stat_completion_t $dc, &$data) : int
{
    return zoo_awget_children2_($zh, $path, $watch ? $zh->watcher : 0, $zh->context, $dc, $data);
}

function zoo_awget_children2(zhandle_t &$zh, string &$path, watcher_fn $watcher, &$watcherCtx,
         strings_stat_completion_t $dc, &$data) : int
{
    return zoo_awget_children2_($zh, $path, $watcher, $watcherCtx, $dc, $data);
}


function zoo_async(zhandle_t &$zh, string &$path, string_completion_t $completion, &$data) : int
{
    /*
    struct oarchive *oa;
    struct RequestHeader h = { STRUCT_INITIALIZER (xid , get_xid()), STRUCT_INITIALIZER (type , ZOO_SYNC_OP)};
    struct SyncRequest req;
    int rc = Request_path_init(zh, 0, &req.path, path);
    //*/
    $oa = new oarchive();
    $h = new RequestHeader(get_xid(), ZOO_SYNC_OP);
    $req = new SyncRequest();
    $rc = Request_path_init($zh, 0, $req->path, $path);
    
    if ($rc != ZOK) {
        return $rc;
    }
    $oa = create_buffer_oarchive();
    $rc = Serialize::serialize_RequestHeader($oa, "header", $h);
    $rc = $rc < 0 ? $rc : Serialize::serialize_SyncRequest($oa, "req", $req);
    enter_critical($zh);
    $rc = $rc < 0 ? $rc : add_string_completion($zh, $h->xid, $completion, $data);
    $rc = $rc < 0 ? $rc : queue_buffer_bytes($zh->to_send, get_buffer($oa), get_buffer_len($oa));
    leave_critical($zh);
    free_duplicate_path($req->path, $path);
    /* We queued the buffer, so don't free it */
    close_buffer_oarchive($oa, 0);

    LOG_DEBUG(sprintf("Sending request xid=%#x for path [%s] to %s", $h->xid, $path, format_current_endpoint_info($zh)));
    /* make a best (non-blocking) effort to send the requests asap */
    adaptor_send_queue($zh, 0);
    return ($rc < 0) ? ZMARSHALLINGERROR : ZOK;
}


function zoo_aget_acl(zhandle_t &$zh, string &$path, acl_completion_t $completion, &$data) : int
{
    /*
    struct oarchive *oa;
    struct RequestHeader h = { STRUCT_INITIALIZER (xid , get_xid()), STRUCT_INITIALIZER(type ,ZOO_GETACL_OP)};
    struct GetACLRequest req;
    int rc = Request_path_init(zh, 0, &req.path, path) ;
    //*/
    $oa = new oarchive();
    $h = new RequestHeader(get_xid(), ZOO_GETACL_OP);
    $req = new GetACLRequest();
    $rc = Request_path_init($zh, 0, $req->path, $path);
    
    if ($rc != ZOK) {
        return $rc;
    }
    $oa = create_buffer_oarchive();
    $rc = Serialize::serialize_RequestHeader($oa, "header", $h);
    $rc = $rc < 0 ? $rc : Serialize::serialize_GetACLRequest($oa, "req", $req);
    enter_critical($zh);
    $rc = $rc < 0 ? $rc : add_acl_completion($zh, $h->xid, $completion, $data);
    $rc = $rc < 0 ? $rc : queue_buffer_bytes($zh->to_send, get_buffer($oa), get_buffer_len($oa));
    leave_critical($zh);
    free_duplicate_path($req->path, $path);
    /* We queued the buffer, so don't free it */
    close_buffer_oarchive($oa, 0);

    LOG_DEBUG(sprintf("Sending request xid=%#x for path [%s] to %s", $h->xid, $path, format_current_endpoint_info($zh)));
    /* make a best (non-blocking) effort to send the requests asap */
    adaptor_send_queue($zh, 0);
    return ($rc < 0) ? ZMARSHALLINGERROR : ZOK;
}

function zoo_aset_acl(zhandle_t &$zh, string &$path, int $version,
        ACL_vector &$acl, void_completion_t $completion, &$data) : int
{
    /*
    struct oarchive *oa;
    struct RequestHeader h = { STRUCT_INITIALIZER(xid ,get_xid()), STRUCT_INITIALIZER (type , ZOO_SETACL_OP)};
    struct SetACLRequest req;
    int rc = Request_path_init(zh, 0, &req.path, path);
    //*/
    $oa = new oarchive();
    $h = new RequestHeader(get_xid(), ZOO_SETACL_OP);
    $req = new SetACLRequest();
    $rc = Request_path_init($zh, 0, $req->path, $path);
    
    if ($rc != ZOK) {
        return $rc;
    }
    $oa = create_buffer_oarchive();
    $req->acl = &$acl;
    $req->version = $version;
    $rc = Serialize::serialize_RequestHeader($oa, "header", $h);
    $rc = $rc < 0 ? $rc : Serialize::serialize_SetACLRequest($oa, "req", $req);
    enter_critical($zh);
    $rc = $rc < 0 ? $rc : add_void_completion($zh, $h->xid, $completion, $data);
    $rc = $rc < 0 ? $rc : queue_buffer_bytes($zh->to_send, get_buffer($oa), get_buffer_len($oa));
    leave_critical($zh);
    free_duplicate_path($req->path, $path);
    /* We queued the buffer, so don't free it */
    close_buffer_oarchive($oa, 0);

    LOG_DEBUG(sprintf("Sending request xid=%#x for path [%s] to %s", $h->xid, $path, format_current_endpoint_info($zh)));
    /* make a best (non-blocking) effort to send the requests asap */
    adaptor_send_queue($zh, 0);
    return ($rc < 0) ? ZMARSHALLINGERROR : ZOK;
}

/* Completions for multi-op results */
function op_result_string_completion(int $err, string &$value, &$data)
{
    $result = new zoo_op_result();
    $result = &$data;
    assert($result);
    $result->err = $err;
    
    if ($result->value && $value) {
        $len = strlen($value) + 1;
        if ($len > $result->valuelen) {
            $len = $result->valuelen;
        }
        if ($len > 0) {
            $result->value = substr($value, 0, $len - 1);
            $result->value .= chr(0);
        }
    } else {
        $result->value = NULL;
    }
}

function op_result_void_completion(int $err, &$data)
{
    $result = new zoo_op_result();
    $result = &$data;
    assert($result);
    $result->err = $err;
}

function op_result_stat_completion(int $err, Stat &$stat, &$data)
{
    $result = new zoo_op_result();
    $result = &$data;
    assert($result);
    $result->err = $err;

    if ($result->stat && $err == 0 && $stat) {
        $result->stat = &$stat;
    } else {
        $result->stat = NULL ;
    }
}   

function CheckVersionRequest_init(zhandle_t &$zh, CheckVersionRequest &$req, string &$path, int $version) : int
{
    $rc = 0;
    assert($req);
    $rc = Request_path_init($zh, 0, $req->path, $path);
    if ($rc != ZOK) {
        return $rc;
    }
    $req->version = $version;

    return ZOK;
}

function zoo_amulti(zhandle_t &$zh, int $count, array &$ops, array &$results, void_completion_t $completion, &$data) : int
{
    /*
    struct RequestHeader h = { STRUCT_INITIALIZER(xid, get_xid()), STRUCT_INITIALIZER(type, ZOO_MULTI_OP) };
    struct MultiHeader mh = { STRUCT_INITIALIZER(type, -1), STRUCT_INITIALIZER(done, 1), STRUCT_INITIALIZER(err, -1) };
    struct oarchive *oa = create_buffer_oarchive();
    completion_head_t clist = { 0 };
    //*/
    $h = new RequestHeader(get_xid(), ZOO_MULTI_OP);
    $mh = new MultiHeader( -1, 1, -1);
    $oa = new oarchive();
    $oa = create_buffer_oarchive();
    $clist = new completion_head_t();
    
    $rc = Serialize::serialize_RequestHeader($oa, "header", $h);

    $index = 0;
    for ($index=0; $index < $count; $index++) {
        /*
        const zoo_op_t *op = ops+index;
        zoo_op_result_t *result = results+index;
        completion_list_t *entry = NULL;
        struct MultiHeader mh = { STRUCT_INITIALIZER(type, op->type), STRUCT_INITIALIZER(done, 0), STRUCT_INITIALIZER(err, -1) };
        //*/
        $op = new zoo_op_t();
        $op = $ops[index];
        $result = new zoo_op_result_t();
        $result = $results[$index];
        $entyr = new completion_list_t();
//        $entyr = null;
        $mh = new MultiHeader($op->type, 0, -1);
        $rc = $rc < 0 ? $rc : Serialize::serialize_MultiHeader($oa, "multiheader", $mh);
     
        switch($op->type) {
            case ZOO_CREATE_OP: {
                /*
                struct CreateRequest req;
                
                rc = rc < 0 ? rc : CreateRequest_init(zh, &req, 
                                        op->create_op.path, op->create_op.data, 
                                        op->create_op.datalen, op->create_op.acl, 
                                        op->create_op.flags);
                rc = rc < 0 ? rc : serialize_CreateRequest(oa, "req", &req);
                result->value = op->create_op.buf;
                result->valuelen = op->create_op.buflen;

                enter_critical(zh);
                entry = create_completion_entry(h.xid, COMPLETION_STRING, op_result_string_completion, result, 0, 0); 
                leave_critical(zh);
                free_duplicate_path(req.path, op->create_op.path);
                //*/
                $req = new CreateRequest();
                $rc = $rc < 0 ? $rc : CreateRequest_init($zh, $req, 
                                        $op->create_op->path, $op->create_op->data, 
                                        $op->create_op->datalen, $op->create_op->acl, 
                                        $op->create_op->flags);
                $rc = $rc < 0 ? $rc : Serialize::serialize_CreateRequest($oa, "req", $req);
                $result->value = $op->create_op->buf;
                $result->valuelen = $op->create_op->buflen;

                enter_critical($zh);
                $entry = create_completion_entry($h->xid, COMPLETION_STRING, "op_result_string_completion", $result, 0, 0); 
                leave_critical($zh);
                free_duplicate_path($req->path, $op->create_op->path);
                
                break;
            }

            case ZOO_DELETE_OP: {
                /*
                struct DeleteRequest req;
                rc = rc < 0 ? rc : DeleteRequest_init(zh, &req, op->delete_op.path, op->delete_op.version);
                rc = rc < 0 ? rc : serialize_DeleteRequest(oa, "req", &req);

                enter_critical(zh);
                entry = create_completion_entry(h.xid, COMPLETION_VOID, op_result_void_completion, result, 0, 0); 
                leave_critical(zh);
                free_duplicate_path(req.path, op->delete_op.path);
                //*/
                $req = new DeleteRequest();
                $rc = $rc < 0 ? $rc : DeleteRequest_init($zh, $req, $op->delete_op->path, $op->delete_op->version);
                $rc = $rc < 0 ? $rc : Serialize::serialize_DeleteRequest($oa, "req", $req);

                enter_critical($zh);
                $entry = create_completion_entry($h->xid, COMPLETION_VOID, "op_result_void_completion", $result, 0, 0); 
                leave_critical($zh);
                free_duplicate_path($req->path, $op->delete_op->path);
                
                break;
            }

            case ZOO_SETDATA_OP: {
                /*
                struct SetDataRequest req;
                rc = rc < 0 ? rc : SetDataRequest_init(zh, &req,
                                        op->set_op.path, op->set_op.data, 
                                        op->set_op.datalen, op->set_op.version);
                rc = rc < 0 ? rc : serialize_SetDataRequest(oa, "req", &req);
                result->stat = op->set_op.stat;

                enter_critical(zh);
                entry = create_completion_entry(h.xid, COMPLETION_STAT, op_result_stat_completion, result, 0, 0); 
                leave_critical(zh);
                free_duplicate_path(req.path, op->set_op.path);
                //*/
                $req = new SetDataRequest();
                $rc = $rc < 0 ? $rc : SetDataRequest_init($zh, $req,
                                        $op->set_op->path, $op->set_op->data, 
                                        $op->set_op->datalen, $op->set_op->version);
                $rc = $rc < 0 ? $rc : Serialize::serialize_SetDataRequest($oa, "req", $req);
                $result->stat = $op->set_op->stat;

                enter_critical($zh);
                $entry = create_completion_entry($h->xid, COMPLETION_STAT, "op_result_stat_completion", $result, 0, 0); 
                leave_critical($zh);
                free_duplicate_path($req->path, $op->set_op->path);
                break;
            }

            case ZOO_CHECK_OP: {
                /*
                struct CheckVersionRequest req;
                rc = rc < 0 ? rc : CheckVersionRequest_init(zh, &req,
                                        op->check_op.path, op->check_op.version);
                rc = rc < 0 ? rc : serialize_CheckVersionRequest(oa, "req", &req);

                enter_critical(zh);
                entry = create_completion_entry(h.xid, COMPLETION_VOID, op_result_void_completion, result, 0, 0); 
                leave_critical(zh);
                free_duplicate_path(req.path, op->check_op.path);
                //*/
                $req = new CheckVersionRequest();
                $rc = $rc < 0 ? $rc : CheckVersionRequest_init($zh, $req,
                                        $op->check_op->path, $op->check_op->version);
                $rc = $rc < 0 ? $rc : Serialize::serialize_CheckVersionRequest($oa, "req", $req);

                enter_critical($zh);
                $entry = create_completion_entry($h->xid, COMPLETION_VOID, "op_result_void_completion", $result, 0, 0); 
                leave_critical($zh);
                free_duplicate_path($req->path, $op->check_op->path);
                break;
            } 

            default:
                LOG_ERROR(sprintf("Unimplemented sub-op type=%d in multi-op", $op->type));
                return ZUNIMPLEMENTED; 
        }

        queue_completion($clist, $entry, 0);
    }

    $rc = $rc < 0 ? $rc : Serialize::serialize_MultiHeader($oa, "multiheader", $mh);
  
    /* BEGIN: CRTICIAL SECTION */
    enter_critical($zh);
    $rc = $rc < 0 ? $rc : add_multi_completion($zh, $h->xid, $completion, $data, $clist);
    $rc = $rc < 0 ? $rc : queue_buffer_bytes($zh->to_send, get_buffer($oa), get_buffer_len($oa));
    leave_critical($zh);
    
    /* We queued the buffer, so don't free it */
    close_buffer_oarchive($oa, 0);

    LOG_DEBUG(sprintf("Sending multi request xid=%#x with %d subrequests to %s",
            $h->xid, $index, format_current_endpoint_info($zh)));
    /* make a best (non-blocking) effort to send the requests asap */
    adaptor_send_queue($zh, 0);

    return ($rc < 0) ? ZMARSHALLINGERROR : ZOK;
}

function zoo_create_op_init(zoo_op_t &$op, string &$path, string &$value,
        int $valuelen,  ACL_vector &$acl, int $flags, 
        string &$path_buffer, int $path_buffer_len)
{
    assert($op);
    $op->type = ZOO_CREATE_OP;
    $op->create_op->path = $path;
    $op->create_op->data = $value;
    $op->create_op->datalen = $valuelen;
    $op->create_op->acl = $acl;
    $op->create_op->flags = $flags;
    $op->create_op->buf = $path_buffer;
    $op->create_op->buflen = $path_buffer_len;
}

function zoo_delete_op_init(zoo_op_t &$op, string &$path, int $version)
{
    assert($op);
    $op->type = ZOO_DELETE_OP;
    $op->delete_op->path = $path;
    $op->delete_op->version = $version;
}

function zoo_set_op_init(zoo_op_t &$op, string &$path, string &$buffer, 
        int $buflen, int $version, Stat &$stat)
{
    assert($op);
    $op->type = ZOO_SETDATA_OP;
    $op->set_op->path = $path;
    $op->set_op->data = $buffer;
    $op->set_op->datalen = $buflen;
    $op->set_op->version = $version;
    $op->set_op->stat = $stat;
}

function zoo_check_op_init(zoo_op_t &$op, string &$path, int $version)
{
    assert($op);
    $op->type = ZOO_CHECK_OP;
    $op->check_op->path = $path;
    $op->check_op->version = $version;
}

function zoo_multi(zhandle_t &$zh, int $count, zoo_op_t &$ops, zoo_op_result_t &$results) : int
{
    $rc = 0;
    $sc = new sync_completion();
    $sc = alloc_sync_completion();
    if (!$sc) {
        return ZSYSTEMERROR;
    }
   
    $rc = zoo_amulti($zh, $count, $ops, $results, SYNCHRONOUS_MARKER, $sc);
    if ($rc == ZOK) {
        wait_sync_completion($sc);
        $rc = $sc->rc;
    }
    free_sync_completion($sc);

    return $rc;
}

/* returns:
 * -1 if send failed,
 * 0 if send would block while sending the buffer (or a send was incomplete),
 * 1 if success
 */
function send_buffer(resource $fd, buffer_list_t $buff) : int
{
    $len = $buff->len;
    $off = $buff->curr_offset;
    $rc = -1;

    if ($off < 4) {
        /* we need to send the length at the beginning */
        /*
        int nlen = htonl(len);
        char *b = (char*)&nlen;
        rc = zookeeper_send(fd, b + off, sizeof(nlen) - off);
        //*/
        $b = pack("L", $len);
        $rc = zookeeper_send(fd, substr($b , $off, 4 - $off), 4 - $off);
        if ($rc == -1) {
            if (socket_last_error() != WSAEWOULDBLOCK) {
                return -1;
            } else {
                return 0;
            }
        } else {
            $buff->curr_offset  += $rc;
        }
        $off = $buff->curr_offset;
    }
    if ($off >= 4) {
        /* want off to now represent the offset into the buffer */
        $off -= 4;
        $rc = zookeeper_send($fd, substr($buff->buffer, $off, strlen($len - $off)), $len - $off);
        if ($rc == -1) {
            if (socket_last_error() != WSAEWOULDBLOCK) {
                return -1;
            }
        } else {
            $buff->curr_offset += $rc;
        }
    }
    return $buff->curr_offset == $len + 4;
}

/* specify timeout of 0 to make the function non-blocking */
/* timeout is in milliseconds */
function flush_send_queue(zhandle_t &$zh, int $timeout) : int
{
    $rc = ZOK;
    $started = new timeval();
    gettimeofday($started,0);
    // we can't use dequeue_buffer() here because if (non-blocking) send_buffer()
    // returns EWOULDBLOCK we'd have to put the buffer back on the queue.
    // we use a recursive lock instead and only dequeue the buffer if a send was
    // successful
    lock_buffer_list($zh->to_send);
    echo __FUNCTION__, " ", __LINE__, "\n";
    var_dump($zh->state, ZOO_CONNECTED_STATE);
    while ($zh->to_send->head && $zh->state == ZOO_CONNECTED_STATE) {
    echo __FUNCTION__, " ", __LINE__, "\n";
        if($timeout != 0){
            $elapsed = 0;
            $now = new timeval();
            gettimeofday($now,0);
            $elapsed = calculate_interval($started, $now);
            if ($elapsed > $timeout) {
                $rc = ZOPERATIONTIMEOUT;
                break;
            }
            $fds = new pollfd();
            $fds->fd = &$zh->fd;
            $fds->events = POLLOUT;
            $fds->revents = 0;
            //这里需要仔细处理：POLL在PHP中，没有对应函数，只能用socket_select来替代。
            $rc = socket_select( null, [$fds->fd], null, $timeout - $elapsed );
//            rc = poll(&fds, 1, $timeout - $elapsed);

            if (!$rc) {
                /* timed out or an error or POLLERR */
                $rc = $rc == 0 ? ZOPERATIONTIMEOUT : ZSYSTEMERROR;
                break;
            }
        }

        $rc = send_buffer($zh->fd, $zh->to_send->head);
        if($rc==0 && $timeout==0){
            /* send_buffer would block while sending this buffer */
            $rc = ZOK;
            break;
        }
        if ($rc < 0) {
            $rc = ZCONNECTIONLOSS;
            break;
        }
        // if the buffer has been sent successfully, remove it from the queue
        if (rc > 0)
            remove_buffer($zh->to_send);
        gettimeofday($zh->last_send, 0);
        $rc = ZOK;
    }
    unlock_buffer_list($zh->to_send);
    return $rc;
}
function strerror(int $errnum){
    $errno = [
        0 => 'Success',
        1 => 'Operation not permitted',
        2 => 'No such file or directory',
        3 => 'No such process',
        4 => 'Interrupted system call',
        5 => 'Input/output error',
        6 => 'No such device or address',
        7 => 'Argument list too long',
        8 => 'Exec format error',
        9 => 'Bad file descriptor',
        10 => 'No child processes',
        11 => 'Resource temporarily unavailable',
        12 => 'Cannot allocate memory',
        13 => 'Permission denied',
        14 => 'Bad address',
        15 => 'Block device required',
        16 => 'Device or resource busy',
        17 => 'File exists',
        18 => 'Invalid cross-device link',
        19 => 'No such device',
        20 => 'Not a directory',
        21 => 'Is a directory',
        22 => 'Invalid argument',
        23 => 'Too many open files in system',
        24 => 'Too many open files',
        25 => 'Inappropriate ioctl for device',
        26 => 'Text file busy',
        27 => 'File too large',
        28 => 'No space left on device',
        29 => 'Illegal seek',
        30 => 'Read-only file system',
        31 => 'Too many links',
        32 => 'Broken pipe',
        33 => 'Numerical argument out of domain',
        34 => 'Numerical result out of range',
        35 => 'Resource deadlock avoided',
        36 => 'File name too long',
        37 => 'No locks available',
        38 => 'Function not implemented',
        39 => 'Directory not empty',
        40 => 'Too many levels of symbolic links',
        41 => 'Unknown error 41',
        42 => 'No message of desired type',
        43 => 'Identifier removed',
        44 => 'Channel number out of range',
        45 => 'Level 2 not synchronized',
        46 => 'Level 3 halted',
        47 => 'Level 3 reset',
        48 => 'Link number out of range',
        49 => 'Protocol driver not attached',
        50 => 'No CSI structure available',
        51 => 'Level 2 halted',
        52 => 'Invalid exchange',
        53 => 'Invalid request descriptor',
        54 => 'Exchange full',
        55 => 'No anode',
        56 => 'Invalid request code',
        57 => 'Invalid slot',
        58 => 'Unknown error 58',
        59 => 'Bad font file format',
        60 => 'Device not a stream',
        61 => 'No data available',
        62 => 'Timer expired',
        63 => 'Out of streams resources',
        64 => 'Machine is not on the network',
        65 => 'Package not installed',
        66 => 'Object is remote',
        67 => 'Link has been severed',
        68 => 'Advertise error',
        69 => 'Srmount error',
        70 => 'Communication error on send',
        71 => 'Protocol error',
        72 => 'Multihop attempted',
        73 => 'RFS specific error',
        74 => 'Bad message',
        75 => 'Value too large for defined data type',
        76 => 'Name not unique on network',
        77 => 'File descriptor in bad state',
        78 => 'Remote address changed',
        79 => 'Can not access a needed shared library',
        80 => 'Accessing a corrupted shared library',
        81 => '.lib section in a.out corrupted',
        82 => 'Attempting to link in too many shared libraries',
        83 => 'Cannot exec a shared library directly',
        84 => 'Invalid or incomplete multibyte or wide character',
        85 => 'Interrupted system call should be restarted',
        86 => 'Streams pipe error',
        87 => 'Too many users',
        88 => 'Socket operation on non-socket',
        89 => 'Destination address required',
        90 => 'Message too long',
        91 => 'Protocol wrong type for socket',
        92 => 'Protocol not available',
        93 => 'Protocol not supported',
        94 => 'Socket type not supported',
        95 => 'Operation not supported',
        96 => 'Protocol family not supported',
        97 => 'Address family not supported by protocol',
        98 => 'Address already in use',
        99 => 'Cannot assign requested address',
        100 => 'Network is down',
        101 => 'Network is unreachable',
        102 => 'Network dropped connection on reset',
        103 => 'Software caused connection abort',
        104 => 'Connection reset by peer',
        105 => 'No buffer space available',
        106 => 'Transport endpoint is already connected',
        107 => 'Transport endpoint is not connected',
        108 => 'Cannot send after transport endpoint shutdown',
        109 => 'Too many references: cannot splice',
        110 => 'Connection timed out',
        111 => 'Connection refused',
        112 => 'Host is down',
        113 => 'No route to host',
        114 => 'Operation already in progress',
        115 => 'Operation now in progress',
        116 => 'Stale file handle',
        117 => 'Structure needs cleaning',
        118 => 'Not a XENIX named type file',
        119 => 'No XENIX semaphores available',
        120 => 'Is a named type file',
        121 => 'Remote I/O error',
        122 => 'Disk quota exceeded',
        123 => 'No medium found',
        124 => 'Wrong medium type',
        125 => 'Operation canceled',
        126 => 'Required key not available',
        127 => 'Key has expired',
        128 => 'Key has been revoked',
        129 => 'Key was rejected by service',
        130 => 'Owner died',
        131 => 'State not recoverable',
        132 => 'Operation not possible due to RF-kill',
        133 => 'Memory page has hardware error',
        ];
    $errmsg = isset($errno[$errnum]) ? $errno[$errnum] : "Unknown error $errnum";
    return $errmsg;
}
function zerror(int $c) : string
{
    switch ($c){
    case ZOK:
      return "ok";
    case ZSYSTEMERROR:
      return "system error";
    case ZRUNTIMEINCONSISTENCY:
      return "run time inconsistency";
    case ZDATAINCONSISTENCY:
      return "data inconsistency";
    case ZCONNECTIONLOSS:
      return "connection loss";
    case ZMARSHALLINGERROR:
      return "marshalling error";
    case ZUNIMPLEMENTED:
      return "unimplemented";
    case ZOPERATIONTIMEOUT:
      return "operation timeout";
    case ZBADARGUMENTS:
      return "bad arguments";
    case ZINVALIDSTATE:
      return "invalid zhandle state";
    case ZAPIERROR:
      return "api error";
    case ZNONODE:
      return "no node";
    case ZNOAUTH:
      return "not authenticated";
    case ZBADVERSION:
      return "bad version";
    case  ZNOCHILDRENFOREPHEMERALS:
      return "no children for ephemerals";
    case ZNODEEXISTS:
      return "node exists";
    case ZNOTEMPTY:
      return "not empty";
    case ZSESSIONEXPIRED:
      return "session expired";
    case ZINVALIDCALLBACK:
      return "invalid callback";
    case ZINVALIDACL:
      return "invalid acl";
    case ZAUTHFAILED:
      return "authentication failed";
    case ZCLOSING:
      return "zookeeper is closing";
    case ZNOTHING:
      return "(not error) no server responses to process";
    case ZSESSIONMOVED:
      return "session moved to another server, so operation is ignored";
    }
    if ($c > 0) {
      return strerror($c);
    }
    return "unknown error";
}


function zoo_add_auth(zhandle_t &$zh, string &$scheme, string &$cert, int $certLen, void_completion_t $completion, &$data) : int
{
    /*
    struct buffer auth;
    auth_info *authinfo;
    //*/
    $auth = new Buffer();
    $authinfo = new auth_info();
    if(!$scheme || !$zh)
        return ZBADARGUMENTS;

    if (is_unrecoverable($zh))
        return ZINVALIDSTATE;

    // [ZOOKEEPER-800] zoo_add_auth should return ZINVALIDSTATE if
    // the connection is closed. 
    if (zoo_state($zh) == 0) {
        return ZINVALIDSTATE;
    }

    if($cert && $certLen != 0){
        /*
        auth->buff = calloc(1,certLen);
        if(auth.buff==0) {
            return ZSYSTEMERROR;
        }
        memcpy(auth.buff,cert,certLen);
        //*/
        $auth->buff = substr($cert, 0, $certLen);
        $auth->len = $certLen;
    } else {
        $auth->buff = 0;
        $auth->len = 0;
    }

    zoo_lock_auth($zh);
//    authinfo = (auth_info*) malloc(sizeof(auth_info));
    $authinfo->scheme = $scheme;
    $authinfo->auth = $auth;
    $authinfo->completion = $completion;
    $authinfo->data = $data;
    $authinfo->next = $NULL;
    add_last_auth($zh->auth_h, $authinfo);
    zoo_unlock_auth($zh);

    if($zh->state == ZOO_CONNECTED_STATE || $zh->state == ZOO_ASSOCIATING_STATE)
        return send_last_auth_info($zh);

    return ZOK;
}


function setup_random()
{
}

#ifndef __CYGWIN__
/**
 * get the errno from the return code 
 * of get addrinfo. Errno is not set
 * with the call to getaddrinfo, so thats
 * why we have to do this.
 */
function getaddrinfo_errno(int $rc) : int
{
    switch($rc) {
    case EAI_NONAME:
// ZOOKEEPER-1323 EAI_NODATA and EAI_ADDRFAMILY are deprecated in FreeBSD.
#if defined EAI_NODATA && EAI_NODATA != EAI_NONAME
    case EAI_NODATA:
#endif
        return ENOENT;
    case EAI_MEMORY:
        return ENOMEM;
    default:
        return EINVAL;
    }
}
#endif

/**
 * fill in the addrs array of the zookeeper servers in the zhandle. after filling
 * them in, we will permute them for load balancing.
 */
function getaddrs(zhandle_t &$zh) : int
{
    /*
    char *hosts = strdup(zh->hostname);
    char *host;
    char *strtok_last;
    struct sockaddr_storage *addr;
    int i;
    int rc;
    /* the allocated length of the addrs array */
//    int alen = 0; 
    $hosts = $zh->hostname;
    $host = '';
    $addr = new sockaddr_storage();
    $i = $rc = $alen = 0;
    
    $zh->addrs_count = 0;
    if (!$hosts) {
         LOG_ERROR(("out of memory"), __LINE__, __FUNCTION__);
        $errno = ENOMEM;
        return ZSYSTEMERROR;
    }
    $zh->addrs = [];
    
    $host = explode(',', $hosts);
    if($host){
        foreach($host as $tmp_host){
            $tmp_port = explode(':', $tmp_host);
            $port = $tmp_port[1];
            if (!$port) {
                LOG_ERROR(sprintf("no port in %s", $tmp_host), __LINE__, __FUNCTION__);
                $errno = EINVAL;
                $rc = ZBADARGUMENTS;
                goto fail;
            }
            $port = trim($port);
            if (!$port || $port == 0) {
                LOG_ERROR(sprintf("invalid port in %s", $tmp_host), __LINE__, __FUNCTION__);
                $errno = EINVAL;
                $rc = ZBADARGUMENTS;
                goto fail;
            }
            $ip2host = $tmp_port[0];
            $tmp_addr = gethostbyname($ip2host);
            if(!$tmp_addr && $ip2host != @gethostbyaddr($tmp_addr)){
                $errno = getaddrinfo_errno(EAI_NONAME);
                LOG_ERROR(sprintf("getaddrinfo: %s\n", strerror($errno)), __LINE__, __FUNCTION__);
                $rc = ZSYSTEMERROR;
                goto fail;
            }
            if(strlen($tmp_addr) < 16){
                $sa_family = AF_INET;
            }else{
                $sa_family = AF_INET6;
            }
            $addr->sa_family = $sa_family;
            $addr->padding = $tmp_host;
            $addr->sa_len = strlen($tmp_host);
            $zh->addrs[] = $addr;
            $zh->addrs_count++;
        }
    }
    global $disable_conn_permute;
    if(!$disable_conn_permute){
        /* Permute */
        for ($i = $zh->addrs_count - 1; $i > 0; --$i) {
            $j = mt_rand(0, 100) % ($i+1);
            if ($i != $j) {
                $t = $zh->addrs[$i];
                $zh->addrs[$i] = $zh->addrs[$j];
                $zh->addrs[$j] = $t;
            }
        }
    }
    return ZOK;
fail:
    if ($zh->addrs) {
        $zh->addrs=[];
    }
    if ($hosts) {
        $hosts = '';
    }
    return $rc;
}

function zoo_client_id(zhandle_t &$zh) : clientid_t
{
    return $zh->client_id;
}

function null_watcher_fn(zhandle_t &$p1, int $p2, int $p3, string &$p4, &$p5) : callback
{
    $null_watcher_fn = function (zhandle_t &$p1, int $p2, int $p3, string &$p4, &$p5){};
    return $null_watcher_fn;
}

function zoo_set_watcher(zhandle_t &$zh, watcher_fn $newFn) : watcher_fn
{
//    watcher_fn oldWatcher = zh->watcher;
    $oldWatcher = $zh->watcher;
    if ($newFn) {
       $zh->watcher = $newFn;
    } else {
       $zh->watcher = null_watcher_fn;
    }
    return $oldWatcher;
}

function zookeeper_get_connected_host(zhandle_t &$zh, string &$addr, int &$port = null) : string
{
    if ($zh->state!=ZOO_CONNECTED_STATE) {
        return NULL;
    }
    if (socket_getpeername($zh->fd, $addr, $port)==-1) {
        return NULL;
    }
    return $addr;
}

function log_env() {
//  char buf[2048];
  $buf = '';
  $utsname = new utsname();

  LOG_INFO(sprintf("Client environment:zookeeper.version=%s", PACKAGE_STRING), __LINE__, __FUNCTION__);

  $buf = getenv('HOSTNAME');
  if($buf){
    LOG_INFO(sprintf("Client environment:host.name=%s", $buf), __LINE__, __FUNCTION__);
  }else{
    LOG_INFO(sprintf("Client environment:host.name=<not implemented>"), __LINE__, __FUNCTION__);
  }

  uname($utsname);
  if($utsname->sysname){
    LOG_INFO(sprintf("Client environment:os.name=%s", $utsname->sysname), __LINE__, __FUNCTION__);
  }else{
    LOG_INFO(sprintf("Client environment:os.name=<not implemented>"), __LINE__, __FUNCTION__);
  }
  if($utsname->release){
    LOG_INFO(sprintf("Client environment:os.arch=%s", $utsname->release), __LINE__, __FUNCTION__);
  }else{
    LOG_INFO(sprintf("Client environment:os.arch=<not implemented>"), __LINE__, __FUNCTION__);
  }
  if($utsname->version){
    LOG_INFO(sprintf("Client environment:os.version=%s", $utsname->version), __LINE__, __FUNCTION__);
  }else{
    LOG_INFO(sprintf("Client environment:os.version=<not implemented>"), __LINE__, __FUNCTION__);
  }

  if($username = getenv('USER')){
    LOG_INFO(sprintf("Client environment:user.name=%s", $username), __LINE__, __FUNCTION__);
  }else{
    LOG_INFO(sprintf("Client environment:user.name=<not implemented>"), __LINE__, __FUNCTION__);
  }

  if ($buf = getenv('HOME')) {
    LOG_INFO(sprintf("Client environment:user.home=%s", $buf), __LINE__, __FUNCTION__);
  } else {
    LOG_INFO(sprintf("Client environment:user.home=<NA>"), __LINE__, __FUNCTION__);
  }

  if ($buf = getenv('PWD')) {
    LOG_INFO(sprintf("Client environment:user.dir=%s", $buf), __LINE__, __FUNCTION__);
  } else {
    LOG_INFO(sprintf("Client environment:user.dir=<not implemented>"), __LINE__, __FUNCTION__);
  }
}

/**
 * Create a zookeeper handle associated with the given host and port.
 */
function zookeeper_init(string $host, watcher_fn $watcher,
  int $recv_timeout, clientid_t $clientid, $context, int $flags)
{
    $errnosave = $errno = 0;
    $zh = NULL;
    $index_chroot = NULL;

    log_env();
    LOG_INFO(sprintf("Initiating client connection, host=%s sessionTimeout=%d watcher=%p sessionId=%#llx sessionPasswd=%s context=%p flags=%d",
              $host,
              $recv_timeout,
              $watcher,
              ($clientid->client_id == 0 ? 0 : $clientid->client_id),
              (($clientid->client_id == 0) || $clientid->passwd ?
               "<null>" : "<hidden>"),
              $context,
              $flags), __LINE__, __FUNCTION__);
    
    $zh = new zhandle_t();
    $zh->fd = -1;
    $zh->state = NOTCONNECTED_STATE_DEF;
    $zh->context = $context;
    $zh->recv_timeout = $recv_timeout;
    init_auth_info($zh->auth_h);
    if ($watcher) {
       $zh->watcher = $watcher;
    } else {
       $zh->watcher = null_watcher_fn();
    }
    if (!$host) { // what we shouldn't dup
        $errno = EINVAL;
        goto abort;
    }
    //parse the host to get the chroot if
    //available
    $index_chroot = strchr($host, '/');
    if ($index_chroot) {
        $zh->chroot = $index_chroot;
        if ($zh->chroot == NULL) {
            goto abort;
        }
        // if chroot is just / set it to null
        if (strlen($zh->chroot) == 1) {
            $zh->chroot = NULL;
        }
        $zh->hostname = substr($host, 0, strlen($host) - strlen($index_chroot));
    } else {
        $zh->chroot = NULL;
        $zh->hostname = $host;
    }
    if ($zh->chroot && !isValidPath($zh->chroot, 0)) {
        $errno = EINVAL;
        goto abort;
    }
    if (!$zh->hostname) {
        echo __FUNCTION__, " ", __LINE__, "\n";
        goto abort;
    }
    if(getaddrs($zh) != 0) {
        echo __FUNCTION__, " ", __LINE__, "\n";
        goto abort;
    }
    $zh->connect_index = 0;
    if ($clientid) {
        $zh->client_id = $clientid;
    } else {
        $zh->client_id = 0;
    }
    $zh->primer_buffer->buffer = $zh->primer_storage_buffer;
    $zh->primer_buffer->curr_offset = 0;
    $zh->primer_buffer->len = strlen($zh->primer_storage_buffer);
    $zh->primer_buffer->next = 0;
    $zh->last_zxid = 0;
    $zh->next_deadline->tv_sec = $zh->next_deadline->tv_usec = 0;
    $zh->socket_readable->tv_sec = $zh->socket_readable->tv_usec = 0;
    $zh->active_node_watchers = create_zk_hashtable();
    $zh->active_exist_watchers = create_zk_hashtable();
    $zh->active_child_watchers = create_zk_hashtable();

    if (adaptor_init($zh) == -1) {
        echo __FUNCTION__, " ", __LINE__, "\n";
        goto abort;
    }

    return $zh;
abort:
    $errnosave = $errno;
    destroy($zh);
    unset($zh);
    $errno = $errnosave;
    return 0;
}

function zookeeper_interest(zhandle_t &$zh, int &$fd, int &$interest, timeval &$tv) : int
{
//    struct timeval now;
    $now = new timeval();
    if($zh == 0 || $fd == 0 || $interest == 0 || $tv == 0)
        return ZBADARGUMENTS;
    if (is_unrecoverable($zh))
        return ZINVALIDSTATE;
    gettimeofday($now, 0);
    if($zh->next_deadline->tv_sec != 0 || $zh->next_deadline->tv_usec != 0){
        $time_left = calculate_interval($zh->next_deadline, $now);
        if ($time_left > 10)
            LOG_WARN(sprintf("Exceeded deadline by %dms", $time_left), __LINE__, __FUNCTION__);
    }
    api_prolog($zh);
    $fd = $zh->fd;
    $interest = 0;
    $tv->tv_sec = 0;
    $tv->tv_usec = 0;
    if ($fd == -1) {
        if ($zh->connect_index == $zh->addrs_count) {
            /* Wait a bit before trying again so that we don't spin */
            $zh->connect_index = 0;
        }else {
            $rc = $ssoresult = 0;
            $enable_tcp_nodelay = 1;

            $zh->fd = socket_create($zh->addrs[$zh->connect_index]->ss_family, SOCK_STREAM, 0);
            if ($zh->fd < 0) {
                return api_epilog($zh, handle_socket_error_msg($zh,__LINE__, ZSYSTEMERROR, "socket() call failed"));
            }
            $ssoresult = socket_set_option($zh->fd, SOL_SOCKET, TCP_NODELAY, $enable_tcp_nodelay);
            if ($ssoresult != 0) {
                LOG_WARN(("Unable to set TCP_NODELAY, operation latency may be effected"), __LINE__, __FUNCTION__);
            }
//            fcntl(zh->fd, F_SETFL, O_NONBLOCK | fcntl(zh->fd, F_GETFL, 0));
            socket_set_block($zh->fd);
            if ($zh->addrs[$zh->connect_index]->ss_family == AF_INET6) {
                $ipport = explode(':', $zh->addrs[$zh->connect_index]->padding);
                $rc = socket_connect($zh->fd, $ipport[0], $ipport[1]);
            } else {
                $ipport = explode(':', $zh->addrs[$zh->connect_index]->padding);
                $rc = socket_connect($zh->fd, $ipport[0], $ipport[1]);
            }
            if ($rc == -1) {
                /* we are handling the non-blocking connect according to
                 * the description in section 16.3 "Non-blocking connect"
                 * in UNIX Network Programming vol 1, 3rd edition */
                if ($errno == EWOULDBLOCK || $errno == EINPROGRESS)
                    $zh->state = ZOO_CONNECTING_STATE;
                else
                    return api_epilog($zh, handle_socket_error_msg($zh, __LINE__, ZCONNECTIONLOSS, "connect() call failed"));
            } else {
                if(($rc = prime_connection($zh)) != 0)
                    return api_epilog($zh, $rc);

                LOG_INFO(sprintf("Initiated connection to server [%s]", format_endpoint_info($zh->addrs[$zh->connect_index])), __LINE__, __FUNCTION__);
            }
        }
        $fd = $zh->fd;
        $tv = get_timeval($zh->recv_timeout/3);
        $zh->last_recv = $now;
        $zh->last_send = $now;
        $zh->last_ping = $now;
    }
    if ($zh->fd != -1) {
        $idle_recv = calculate_interval($zh->last_recv, $now);
        $idle_send = calculate_interval($zh->last_send, $now);
        $recv_to = $zh->recv_timeout*2/3 - $idle_recv;
        $send_to = $zh->recv_timeout/3;
        // have we exceeded the receive timeout threshold?
        if ($recv_to <= 0) {
            // We gotta cut our losses and connect to someone else
            $errno = ETIMEDOUT;
            $interest = 0;
            $tv = get_timeval(0);
            return api_epilog($zh, handle_socket_error_msg($zh,
                    __LINE__, ZOPERATIONTIMEOUT,
                    "connection to %s timed out (exceeded timeout by %dms)",
                    format_endpoint_info($zh->addrs[$zh->connect_index]),
                    -$recv_to));

        }
        // We only allow 1/3 of our timeout time to expire before sending
        // a PING
        if ($zh->state == ZOO_CONNECTED_STATE) {
            $send_to = $zh->recv_timeout/3 - $idle_send;
            if ($send_to <= 0) {
                if ($zh->sent_requests->head==0) {
//                    LOG_DEBUG(("Sending PING to %s (exceeded idle by %dms)",
//                                    format_current_endpoint_info(zh),-send_to));
                    $rc = send_ping($zh);
                    if ($rc < 0){
                        LOG_ERROR(sprintf("failed to send PING request (zk retcode=%d)",rc), __LINE__, __FUNCTION__);
                        return api_epilog($zh,rc);
                    }
                }
                $send_to = $zh->recv_timeout/3;
            }
        }
        // choose the lesser value as the timeout
        $tv = get_timeval($recv_to < $send_to ? $recv_to : $send_to);
        $zh->next_deadline->tv_sec = $now->tv_sec + $tv->tv_sec;
        $zh->next_deadline->tv_usec = $now->tv_usec + $tv->tv_usec;
        if ($zh->next_deadline->tv_usec > 1000000) {
            $zh->next_deadline->tv_sec += $zh->next_deadline->tv_usec / 1000000;
            $zh->next_deadline->tv_usec = $zh->next_deadline->tv_usec % 1000000;
        }
        $interest = ZOOKEEPER_READ;
        /* we are interested in a write if we are connected and have something
         * to send, or we are waiting for a connect to finish. */
        if (($zh->to_send.head && ($zh->state == ZOO_CONNECTED_STATE)) || $zh->state == ZOO_CONNECTING_STATE) {
            $interest |= ZOOKEEPER_WRITE;
        }
    }
    return api_epilog(zh,ZOK);
}

function send_ping(zhandle_t &$zh) : int
{   
    $rc;
    $oa = create_buffer_oarchive();
    $h = new RequestHeader(PING_XID, ZOO_PING_OP);

    $rc = Serialize::serialize_RequestHeader($oa, "header", $h);
    enter_critical($zh);
    gettimeofday($zh->last_ping, 0);
    $rc = $rc < 0 ? $rc : queue_buffer_bytes($zh->to_send, get_buffer($oa), get_buffer_len($oa));
    leave_critical($zh);
    close_buffer_oarchive($oa, 0);
    return $rc<0 ? $rc : adaptor_send_queue($zh, 0);
}

function get_timeval(int $interval) : timeval
{
    $tv = new timeval();
    if ($interval < 0) {
        $interval = 0;
    }
    $tv->tv_sec = $interval/1000;
    $tv->tv_usec = ($interval%1000)*1000;
    return $tv;
}