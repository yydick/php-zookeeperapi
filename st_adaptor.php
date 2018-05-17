<?php
/************************************************
* 本程序于2018年03月25日
* 由陈浩波编写完成
* 任何人使用时请保留该声明
*/
namespace SPOOL\ZOOKEEPER\CLIENT;

function process_async(int $outstanding_sync) : int
{
    return $outstanding_sync == 0;
}
function zoo_lock_auth(zhandle_t &$zh) : int
{
    return 0;
}
function zoo_unlock_auth(zhandle_t &$zh) : int
{
    return 0;
}
function lock_buffer_list(buffer_head_t &$l) : int
{
    return 0;
}
function unlock_buffer_list(buffer_head_t &$l) : int
{
    return 0;
}
function lock_completion_list(completion_head_t &$l) : int
{
    return 0;
}
function unlock_completion_list(completion_head_t &$l) : int
{
    return 0;
}
function alloc_sync_completion() : sync_completion
{
    return new sync_completion();
//    return (struct sync_completion*)calloc(1, sizeof(struct sync_completion));
}
function wait_sync_completion(sync_completion &$sc) : int
{
    return 0;
}

function free_sync_completion(sync_completion &$sc)
{
    free(sc);
}

function notify_sync_completion(sync_completion &$sc)
{
}

function adaptor_init(zhandle_t &$zh) : int
{
    return 0;
}

function adaptor_finish(zhandle_t &$zh){}

function adaptor_destroy(zhandle_t &$zh){}
/*
function flush_send_queue(zhandle_t &$zh, int $i) : int
{
}
//*/
function adaptor_send_queue(zhandle_t &$zh, int $timeout) : int
{
    return flush_send_queue($zh, $timeout);
}
/*
function inc_ref_counter(zhandle_t &$zh,int $i) : int
{
    $zh->ref_counter += i<0 ? -1 : ( i>0 ? 1 : 0 );
    return $zh->ref_counter;
}
//*/
function get_xid() : int
{
    static $xid = -1;
    if ($xid == -1) {
        $xid = time();
    }
    return $xid++;
}
/*
function enter_critical(zhandle_t &$zh) : int
{
    return 0;
}

function leave_critical(zhandle_t &$zh) : int
{
    return 0;
}
//*/