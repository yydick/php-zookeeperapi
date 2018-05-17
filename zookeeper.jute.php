<?php
/************************************************
* 本程序于2018年03月25日
* 由陈浩波编写完成
* 任何人使用时请保留该声明
*/
namespace SPOOL\ZOOKEEPER\CLIENT;

const PACKAGE_STRING = "zookeeper PHP client 3.4.11 Base 0.0.1";

const PTHREADS = false;

const IPPROTO_TCP = 6;       /* Transmission Control Protocol.  */

const EAI_BADFLAGS = -1;    /* Invalid value for `ai_flags' field.  */
const EAI_NONAME = -2;    /* NAME or SERVICE is unknown.  */
const EAI_AGAIN = -3;    /* Temporary failure in name resolution.  */
const EAI_FAIL = -4;    /* Non-recoverable failure in name res.  */
const EAI_FAMILY = -6;    /* `ai_family' not supported.  */
const EAI_SOCKTYPE = -7;    /* `ai_socktype' not supported.  */
const EAI_SERVICE = -8;    /* SERVICE not supported for `ai_socktype'.  */
const EAI_MEMORY = -10;    /* Memory allocation failure.  */
const EAI_SYSTEM = -11;    /* System error returned in `errno'.  */
const EAI_OVERFLOW = -12;    /* Argument buffer overflow.  */
const EAI_NODATA = -5;    /* No address associated with NAME.  */
const EAI_ADDRFAMILY = -9;    /* Address family for NAME not supported.  */
const EAI_INPROGRESS = -100;    /* Processing request in progress.  */
const EAI_CANCELED = -101;    /* Request canceled.  */
const EAI_NOTCANCELED = -102;    /* Request not canceled.  */
const EAI_ALLDONE = -103;    /* All requests done.  */
const EAI_INTR = -104;    /* Interrupted by a signal.  */
const EAI_IDN_ENCODE = -105;    /* IDN encoding failed.  */

const ENOENT = 2;       //无此文件或目录
const E2BIG = 7;        //定义一个标准错误：linux错误代码 参数列表过长
const EINVAL = 22;      //定义一个标准错误：linux错误代码 无效的参数
const ENOMEM = 12;      //定义一个标准错误：linux错误代码 内存溢出
const ESTALE = 116;    //定义一个标准错误：linux错误代码 Stale NFS file handle
const INT32_T = 4;      //定义int32的字符大小
const INT64_T = 8;      //定义int64的字符大小
const BOOL = 1;         //定义BOOL的字符大小

const PF_UNSPEC = 0;
const PF_INET = 2;
const PF_INET6 = 10;

const AF_UNSPEC = PF_UNSPEC;
const AF_INET = PF_INET;
const AF_INET6 = PF_INET6;

const ZOO_MAJOR_VERSION = 3;
const ZOO_MINOR_VERSION = 4;
const ZOO_PATCH_VERSION = 11;

const ZOOKEEPER_WRITE = 1;
const ZOOKEEPER_READ = 2;

const ZOO_EPHEMERAL = 1;
const ZOO_SEQUENCE = 2;

const ZOK = 0; /*!< Everything is OK */
/** System and server-side errors.
 * This is never thrown by the server; it shouldnt be used other than
 * to indicate a range. Specifically error codes greater than this
 * value; but lesser than{@link #ZAPIERROR}; are system errors.
 */
const ZSYSTEMERROR = -1;
const ZRUNTIMEINCONSISTENCY = -2; /*!< A runtime inconsistency was found */
const ZDATAINCONSISTENCY = -3; /*!< A data inconsistency was found */
const ZCONNECTIONLOSS = -4; /*!< Connection to the server has been lost */
const ZMARSHALLINGERROR = -5; /*!< Error while marshalling or unmarshalling data */
const ZUNIMPLEMENTED = -6; /*!< Operation is unimplemented */
const ZOPERATIONTIMEOUT = -7; /*!< Operation timeout */
const ZBADARGUMENTS = -8; /*!< Invalid arguments */
const ZINVALIDSTATE = -9;
/** API errors.
 * This is never thrown by the server; it shouldnt be used other than
 * to indicate a range. Specifically error codes greater than this
 * value are API errors (while values less than this indicate a
 *{@link #ZSYSTEMERROR}).
 */
const ZAPIERROR = -100;
const ZNONODE = -101; /*!< Node does not exist */
const ZNOAUTH = -102; /*!< Not authenticated */
const ZBADVERSION = -103; /*!< Version conflict */
const ZNOCHILDRENFOREPHEMERALS = -108; /*!< Ephemeral nodes may not have children */
const ZNODEEXISTS = -110; /*!< The node already exists */
const ZNOTEMPTY = -111; /*!< The node has children */
const ZSESSIONEXPIRED = -112; /*!< The session has been expired by the server */
const ZINVALIDCALLBACK = -113; /*!< Invalid callback specified */
const ZINVALIDACL = -114; /*!< Invalid ACL specified */
const ZAUTHFAILED = -115; /*!< Client authentication failed */
const ZCLOSING = -116; /*!< ZooKeeper is closing */
const ZNOTHING = -117; /*!< (not error) no server responses to process */
const ZSESSIONMOVED = -118; /*!<session moved to another server; so operation is ignored */

const ZOO_LOG_LEVEL_ERROR = 1;
const ZOO_LOG_LEVEL_WARN = 2;
const ZOO_LOG_LEVEL_INFO = 3;
const ZOO_LOG_LEVEL_DEBUG = 4;

const ZOO_PERM_READ = 1;
const ZOO_PERM_WRITE = 2;
const ZOO_PERM_CREATE = 4;
const ZOO_PERM_DELETE = 8;
const ZOO_PERM_ADMIN = 16;
const ZOO_PERM_ALL = 31;

const ZOO_NOTIFY_OP = 0;
const ZOO_CREATE_OP = 1;
const ZOO_DELETE_OP = 2;
const ZOO_EXISTS_OP = 3;
const ZOO_GETDATA_OP = 4;
const ZOO_SETDATA_OP = 5;
const ZOO_GETACL_OP = 6;
const ZOO_SETACL_OP = 7;
const ZOO_GETCHILDREN_OP = 8;
const ZOO_SYNC_OP = 9;
const ZOO_PING_OP = 11;
const ZOO_GETCHILDREN2_OP = 12;
const ZOO_CHECK_OP = 13;
const ZOO_MULTI_OP = 14;
const ZOO_CLOSE_OP = -11;
const ZOO_SETAUTH_OP = 100;
const ZOO_SETWATCHES_OP = 101;

const WATCHER_EVENT_XID = -1;
const PING_XID = -2;
const AUTH_XID = -4;
const SET_WATCHES_XID = -8;

const EXPIRED_SESSION_STATE_DEF = -112;
const AUTH_FAILED_STATE_DEF = -113;
const CONNECTING_STATE_DEF = 1;
const ASSOCIATING_STATE_DEF = 2;
const CONNECTED_STATE_DEF = 3;
const NOTCONNECTED_STATE_DEF = 999;

const CREATED_EVENT_DEF = 1;
const DELETED_EVENT_DEF = 2;
const CHANGED_EVENT_DEF = 3;
const CHILD_EVENT_DEF = 4;
const SESSION_EVENT_DEF = -1;
const NOTWATCHING_EVENT_DEF = -2;

const ZOO_EXPIRED_SESSION_STATE = EXPIRED_SESSION_STATE_DEF;
const ZOO_AUTH_FAILED_STATE = AUTH_FAILED_STATE_DEF;
const ZOO_CONNECTING_STATE = CONNECTING_STATE_DEF;
const ZOO_ASSOCIATING_STATE = ASSOCIATING_STATE_DEF;
const ZOO_CONNECTED_STATE = CONNECTED_STATE_DEF;

const ZOO_CREATED_EVENT = CREATED_EVENT_DEF;
const ZOO_DELETED_EVENT = DELETED_EVENT_DEF;
const ZOO_CHANGED_EVENT = CHANGED_EVENT_DEF;
const ZOO_CHILD_EVENT = CHILD_EVENT_DEF;
const ZOO_SESSION_EVENT = SESSION_EVENT_DEF;
const ZOO_NOTWATCHING_EVENT = NOTWATCHING_EVENT_DEF;

const COMPLETION_WATCH = -1;
const COMPLETION_VOID = 0;
const COMPLETION_STAT = 1;
const COMPLETION_DATA = 2;
const COMPLETION_STRINGLIST = 3;
const COMPLETION_STRINGLIST_STAT = 4;
const COMPLETION_ACLLIST = 5;
const COMPLETION_STRING = 6;
const COMPLETION_MULTI = 7;

define('ENOTSOCK',      88);    /* Socket operation on non-socket */ 
define('EDESTADDRREQ',  89);    /* Destination address required */ 
define('EMSGSIZE',      90);    /* Message too long */ 
define('EPROTOTYPE',    91);    /* Protocol wrong type for socket */ 
define('ENOPROTOOPT',   92);    /* Protocol not available */ 
define('EPROTONOSUPPORT', 93);  /* Protocol not supported */ 
define('ESOCKTNOSUPPORT', 94);  /* Socket type not supported */ 
define('EOPNOTSUPP',    95);    /* Operation not supported on transport endpoint */ 
define('EPFNOSUPPORT',  96);    /* Protocol family not supported */ 
define('EAFNOSUPPORT',  97);    /* Address family not supported by protocol */ 
define('EADDRINUSE',    98);    /* Address already in use */ 
define('EADDRNOTAVAIL', 99);    /* Cannot assign requested address */ 
define('ENETDOWN',      100);   /* Network is down */ 
define('ENETUNREACH',   101);   /* Network is unreachable */ 
define('ENETRESET',     102);   /* Network dropped connection because of reset */ 
define('ECONNABORTED',  103);   /* Software caused connection abort */ 
define('ECONNRESET',    104);   /* Connection reset by peer */ 
define('ENOBUFS',       105);   /* No buffer space available */ 
define('EISCONN',       106);   /* Transport endpoint is already connected */ 
define('ENOTCONN',      107);   /* Transport endpoint is not connected */ 
define('ESHUTDOWN',     108);   /* Cannot send after transport endpoint shutdown */ 
define('ETOOMANYREFS',  109);   /* Too many references: cannot splice */ 
define('ETIMEDOUT',     110);   /* Connection timed out */ 
define('ECONNREFUSED',  111);   /* Connection refused */ 
define('EHOSTDOWN',     112);   /* Host is down */ 
define('EHOSTUNREACH',  113);   /* No route to host */ 
define('EALREADY',      114);   /* Operation already in progress */ 
define('EINPROGRESS',   115);   /* Operation now in progress */ 
define('EREMOTEIO',     121);   /* Remote I/O error */ 
define('ECANCELED',     125);   /* Operation Canceled */ 
define('SYNCHRONOUS_MARKER', null); /*尝试将同步标志设置为null*/

const WSAEWOULDBLOCK = ENOBUFS;
const WSAEINPROGRESS = 10036;
const EWOULDBLOCK = WSAEWOULDBLOCK;
const EINPROGRESS = WSAEINPROGRESS;

const POLLIN = 0x0001;
const POLLPRI = 0x0002;
const POLLOUT = 0x0004;
const POLLERR = 0x0008;
const POLLHUP = 0x0010;
const POLLNVAL = 0x0020;

const POLLRDNORM = 0x0040;
const POLLRDBAND = 0x0080;
const POLLWRNORM = 0x0100;
const POLLWRBAND = 0x0200;
const POLLMSG = 0x0400;
const POLLREMOVE = 0x1000;
const POLLRDHUP = 0x2000;

abstract class _buffer_list extends \SplStack
{
    public $buffer;             //string
    public $len;                //int
    public $curr_offset;        //int
    public $next;               //struct _buffer_list *next;
    public function __construct( string &$buffer = '', int $len = 0, int $curr_offset = 0)
    {
        $this->buffer = $buffer;
        $this->len = $len;
        $this->curr_offset = $curr_offset;
        $this->next = null;
    }
}
class buffer_list_t extends _buffer_list
{
}
abstract class _buffer_head
{
    public $head;               //_buffer_list
    public $last;               //_buffer_list
    public $lock;               //pthread_mutex_t

    public function __construct( _buffer_list &$head = null, _buffer_list &$last = null, &$lock = null)
    {
        $this->head = $head;
        $this->last = $last;
        $this->lock = $lock;
//        $this->lock = new \swoole_lock(\SWOOLE_MUTEX);
    }
}
class buffer_head_t extends _buffer_head
{
}

class void_completion_t
{
    public $rc;                 //int
    public $data;               //const void*

    public function __construct(int $rc = 0, &$data = null)
    {
        $this->rc = $rc;
        $this->data = $data;
    }
    public function __invoke(int $rc = 0, &$data = null){}
}
class stat_completion_t
{
    public $rc;                 //int
    public $stat;               //class Stat
    public $data;               //const void*

    public function __construct(int $rc = 0, Stat &$stat = null, &$data = null)
    {
        $this->rc = $rc;
        $this->stat = $stat;
        $this->data = $dat;
    }
    public function __invoke(int $rc = 0, Stat &$stat = null, &$data = null){}
}
class data_completion_t
{
    public $rc;                 //int
    public $value;              //string
    public $value_len;          //int
    public $stat;               //class Stat;
    public $data;               //const void*

    public function __construct(int $rc = 0, string $value = '', int $value_len = 0, Stat &$stat = null, &$data = null)
    {
        $this->rc = $rc;
        $this->value = $value;
        $this->value_len = $value_len;
        $this->stat = $stat;
        $this->data = $data;
    }
    public function __invoke(int $rc, string $value, int $value_len, Stat &$stat = null, &$data = null){}
}
class strings_completion_t
{
    public $rc;                 //int
    public $string;             //String_vector
    public $data;               //const void*

    public function __construct(int $rc = 0, String_vector &$string = null, &$data = null)
    {
        $this->rc = $rc;
        $this->string = $string;
        $this->data = $data;
    }
    public function __invoke(int $rc = 0, String_vector &$strings = null, &$data = null){}
}
abstract class strings_stat_completion_t
{
    public $rc;                 //int
    public $strings;            //String_vector
    public $stat;               //Stat
    public $data;               //const void*

    public function __construct(int $rc = 0, String_vector &$strings = null, Stat &$stat = null, &$data = null)
    {
        $this->rc = $rc;
        $this->strings = $strings;
        $this->stat = $stat;
        $this->data = $data;
    }
    public function __invoke(int $rc = 0, String_vector &$strings = null, Stat &$stat, &$data = null){}
}
class acl_completion_t
{
    public $rc;                 //int
    public $acl;                //ACL_vector
    public $stat;               //Stat
    public $data;               //const void*

    public function __construct(int $rc = 0, ACL_vector &$acl = null, Stat &$stat = null, &$data = null)
    {
        $this->rc = $rc;
        $this->acl = $acl ?: new ACL_vector();
        $this->stat = $sata;
        $this->data = $data;
    }
    public function __invoke(int $rc = 0, ACL_vector &$acl = null, Stat &$stat = null, &$data = null){}
}
class string_completion_t
{
    public $rc = 0;             //int
    public $value = '';         //string
    public $data = null;        //const void*

    public function __construct(int $rc = 0, string &$value = null, &$data = null)
    {
        $this->rc = $rc;
        $this->value = $value;
        $this->data = $data;
    }
    public function __invoke(int $rc = 0, String $value = null, &$data = null){}
}
class watcher_object_t  extends \SplStack
{
    public $watcher;            //watcher_fn
    public $context;            //void *
    public $next;               //_watcher_object*

    public function __construct(watcher_fn &$watcher = null, &$context = null)
    {
        $this->watcher = $watcher;
        $this->context = $context;
        $this->next = null;
    }
}
class watcher_object_list
{
    public $head;               //watcher_object_t array

    public function __construct()
    {
        $this->head = [];
    }
}
abstract class result_checker_fn
{
    public $reg;                //zhandle_t *
    public $rc;                 //int

    public function __construct(zhandle_t &$reg = null, int $rc = 0)
    {
        $this->reg = $reg;
        $this->rc = $rc;
    }
}
abstract class completion
{
    public $type;               /* one of COMPLETION_* values above | int */
    //union start
    public $void_result;        /* void_completion_t */
    public $stat_result;        /* stat_completion_t */
    public $data_result;        /* data_completion_t */
    public $strings_result;     /* strings_completion_t */
    public $strings_stat_result;/* strings_stat_completion_t */
    public $acl_result;         /* acl_completion_t */
    public $string_result;      /* string_completion_t */
    public $watcher_result;     /* watcher_object_list */
    //union end
    public $clist;              /* For multi-op | completion_head_t */

    public function __construct(void_completion_t &$void = null, stat_completion_t &$stat = null, data_completion_t &$data = null,
                    strings_completion_t &$strs = null, strings_stat_completion_t &$str_stat = null, acl_completion_t &$acl = null,
                    string_completion_t &$str = null, watcher_object_list &$wo = null, completion_head_t &$head = null)
    {
        $this->type = 0;
        $this->void_result = $void;
        $this->stat_result = $stat;
        $this->data_result = $data;
        $this->strings_result = $strs;
        $this->strings_stat_result = $str_stat;
        $this->acl_result = $acl;
        $this->string_result = $str;
        $this->watcher_result = $wo;
        $this->clist = $head;
    }
}
class completion_t extends completion
{
}
/*****************************************
* 这里有cond还未实现，需要到实际应用中确认
*/
class _completion_head{
    public $head;               //_completion_list
    public $last;               //_completion_list
    public $cond;               //pthread_cond_t
    public $lock;               //pthread_mutex_t

    public function __construct(_completion_list &$head = null, _completion_list &$last = null, &$cond = null, &$lock = null)
    {
        $this->head = $head;
        $this->last = $last;
        $this->cond = $cond;
        $this->lock = $lock;
//        $this->lock = new \swoole_lock(\SWOOLE_MUTEX);
    }
}
class completion_head_t extends _completion_head{
}
class sync_completion_u_str{
    public $str = '';
    public $str_len = 0;
}
class sync_completion_u_data{
    public $buffer = '';
    public $buff_len = 0;
    public $stat;
    public function __construct(Stat $stat = null){
        $this->stat = $stat;
    }
}
class sync_completion_u_acl{
    public $acl;
    public $stat;
    public function __construct(ACL_vector $acl = null, Stat $stat = null){
        $this->acl = $acl;
        $this->stat = $stat;
    }
}
class sync_completion_u_strs_stat{
    public $strs2;
    public $stat2;
    public function __construct(String_vector $strs2 = null, Stat $stat = null){
        $this->strs2 = $strs2;
        $this->stat2 = $stat;
    }
}
class sync_completion_u{
    public $str;
    public $stat;
    public $data;
    public $acl;
    public $strs2;
    public $strs_stat;
    public function __construct(sync_completion_u_str $str = null, Stat $stat = null, sync_completion_u_data $data = null, ACL_vector $acl = null, String_vector $strs2 = null, sync_completion_u_strs_stat $sync_ss = null){
        $this->str = $str;
        $this->stat = $stat;
        $this->data = $data;
        $this->acl = $acl;
        $this->strs2 = $strs2;
        $this->strs_stat = $sync_ss;
    }
}
class sync_completion{
    public $rc;                 //int
    public $u;                  //union array
    public $complete;           //int
    public $cond;               //pthread_cond_t
    public $lock;               //pthread_mutex_t
    public function __construct(int $rc = 0, sync_completion_u $u = null, int $complate = 0, &$cond = null, &$lock = null)
    {
        $this->rc = $rc;
        $this->complete = $complate;
        $this->cond = $cond;
        $this->lock = $lock;
        $this->u = $u;
    }
}
abstract class _auth_info extends \SplStack
{
    public $state;              /* 0=>inactive, >0 => active | int*/
    public $scheme;             //string
    public $auth;               //class buffer
    public $completion;         //void_completion_t
    public $data;               //string
    public $next;               //_auth_info

    public function __construct(int $state = 0, string &$scheme = '', Buffer &$auth = null, void_completion_t &$completion, string &$data = '')
    {
        $this->state = $state;
        $this->scheme = $scheme;
        $this->auth = $auth;
        $this->completion = $completion;
        $this->data = $data;
        $this->next = null;
    }
}
class auth_info extends _auth_info{
}
abstract class _watcher_registration
{
    public $watcher;            //watcher_fn
    public $context;            //void *
    public $checker;            //result_checker_fn
    public $path;               //string
    
    public function __construct(watcher_fn &$watcher = null, &$context = null, result_checker_fn &$checker = null, string $path = '')
    {
        $this->watcher = $watcher;
        $this->context = $context;
        $this->checker = $checker;
        $this->path    = $path;
    }
}
class watcher_registration_t extends _watcher_registration
{
}
abstract class _completion_list extends \SplStack
{
    public $xid;                //int
    public $c;                  //completion_t
    public $data;               //const void *
    public $buffer;             //buffer_list_t
    public $next;               //_completion_list
    public $watcher;            //watcher_registration_t

    public function __construct(int &$xid = -1, completion_t &$c = null, &$data = null, buffer_list_t &$buffer = null, watcher_registration_t &$watcher = null)
    {
        $this->xid = $xid;
        $this->c = $c;
        $this->data = $data;
        $this->buffer = $buffer;
        $this->next = null;
        $this->watcher = $watcher;
    }
}
class completion_list_t extends _completion_list
{
}
class clientid_t
{
    public $client_id;          //int64_t
    public $passwd;             //string
    
    public function __construct(int &$client_id = 0, string $passwd = ''){
        $this->client_id = $client_id;
        $this->passwd = $passwd;
    }
}
class connect_req
{
    public $protocolVersion;    //int32_t
    public $lastZxidSeen;       //int64_t
    public $timeOut;            //int32_t
    public $sessionId;          //int64_t
    public $passwd_len;         //int32_t
    public $passwd;             //char[16]
    
    public function __construct(int $protocolVersion = 0, int $lastZxidSeen = 0, int $timeOut = 0, int $sessionId = 0, int $passwd_len = 0, string $passwd = ''){
        $this->protocolVersion = $protocolVersion;
        $this->lastZxidSeen = $lastZxidSeen;
        $this->timeOut = $timeOut;
        $this->sessionId = $sessionId;
        $this->passwd_len = $passwd_len;
        $this->passwd = $passwd;
    }
}
class prime_struct
{
    public $len;                //int32_t
    public $protocolVersion;    //int32_t
    public $timeOut;            //int32_t
    public $sessionId;          //int64_t
    public $passwd_len;         //int32_t
    public $passwd;             //char[16]
    
    public function __construct(int $protocolVersion = 0, int $lastZxidSeen = 0, int $timeOut = 0, int $sessionId = 0, int $passwd_len = 0, string $passwd = ''){
        $this->protocolVersion = $protocolVersion;
        $this->lastZxidSeen = $lastZxidSeen;
        $this->timeOut = $timeOut;
        $this->sessionId = $sessionId;
        $this->passwd_len = $passwd_len;
        $this->passwd = $passwd;
    }
}
class zoo_op_result{
    public $err = 0;
    public $value = '';
    public $valuelen = 0;
    public $stat;
    public function __construct(int $err = 0, string $value = '', int $valuelen = 0, Stat $stat = null)
    {
        $this->err = $err;
        $this->value = $value;
        $this->valuelen = $valuelen;
        $this->stat = $stat;
    }
}
class zoo_op_result_t extends zoo_op_result
{
}
class create_op{
    public $path = '';
    public $data = '';
    public $datalen = 0;
    public $buf = '';
    public $buflen = 0;
    public $acl;
    public $flags = 0;
    public function __construct(string $path = '', string $data = '', int $datalen = 0, string $buf = '', int $buflen = 0, ACL_vector $acl = null, int $flags = 0)
    {
        $this->path = $path;
        $this->data = $data;
        $this->datalen = $datalen;
        $this->buf = $buf;
        $this->buflen = $buflen;
        $this->acl = $acl;
        $this->flags = $flags;
    }
}
class delete_op{
    public $path = '';
    public $version = 0;
}
class set_op{
    public $path = '';
    public $data = '';
    public $datalen = 0;
    public $version = 0;
    public $stat;
    public function __construct(string $path = '', string $data = '', int $datalen = 0, int $version = 0, Stat $stat = null)
    {
        $this->path = $path;
        $this->data = $data;
        $this->datalen = $datalen;
        $this->version = $version;
        $this->stat = $stat;
    }
}
class check_op{
    public $path = '';
    public $version = 0;
}
class zoo_op{
    public $type = 0;
    public $create_op;
    public $delete_op;
    public $set_op;
    public $check_op;
    public function __construct(int $type = 0, create_op $create_op = null, delete_op $delete_op = null, 
            set_op $set_op = null, check_op $check_op = null)
    {
        $this->type = $this;
        $this->create_op = $create_op;
        $this->delete_op = $delete_op;
        $this->set_op    = $set_op;
        $this->check_op  = $check_op;
    }
}
class zoo_op_t extends zoo_op
{
}
class adaptor_threads{
    public $io;                 //pthread_t
    public $completion;         //pthread_t
    public $threadsToWait;      //barrier | int
    public $cond;               //barrier's conditional | pthread_cond_t
    public $lock;               // ... and a lock | pthread_mutex_t
    public $zh_lock;            // critical section lock | pthread_mutex_t
    public $self_pipe = [];     //两个线程 | sock fd
    
    public function __construct(&$io = null, &$completion = null, int $threadsToWait = 0, &$cond = null, &$lock = null, &$zh_lock = null, array &$self_pipe = []){
        $this->io = $io;
        $this->completion = $completion;
        $this->threadsToWait = $threadsToWait;
        $this->cond = $cond;
        $this->lock = $lock;
        $this->zh_lock = $zh_lock;
        $this->self_pipe = $self_pipe;
    }
}
abstract class _auth_list_head{
    public $auth;               //auth_info[]
    public $lock;               //pthread_mutex_t
    
    public function __construct(auth_info &$auth = null, &$lock = null)
    {
        $this->auth = $auth;
        $this->lock = $lock;
//        $this->lock = new \swoole_lock(\SWOOLE_MUTEX);
    }
}
class auth_list_head_t extends _auth_list_head{
}
interface watcher_fn
{
    /*******************************************************
    * 预定义watcher_fn接口
    *
    * @param zhandle_t & $zh
    */
    public function watcher_fn(zhandle_t &$zh, int $type, int $state, string &$path, mixed $watcherCtx);
}
class utsname{
    public $sysname;
    public $nodename;
    public $release;
    public $version;
    public $machine;
}
class hashtable_itr
{
    public $h;
    public $e;
    public $parent;
    public $index;
    public function __construct(hashtable &$h = null, entry &$e = null, entry &$parent = null, int $index = 0){
        $this->h = $h;
        $this->e = $e;
        $this->parent = $parent;
        $this->index = $index;
    }
};


function uname(utsname &$utsname){
    $utsname->sysname = php_uname('s');
    $utsname->nodename = php_uname('n');
    $utsname->release = php_uname('r');
    $utsname->version = php_uname('v');
    $utsname->machine = php_uname('m');
}
class sockaddr_storage {
    public $sa_len;                 //int
    public $sa_family;              //int
    public $padding;                //string "IP:PORT"
    
    public function __construct(int $sa_len = 0, int $sa_family = 0, string $padding = '')
    {
        $this->sa_len = $sa_len;
        $this->sa_family = $sa_family;
        $this->padding = $padding;
    }
};

abstract class _zhandle
{
    /* the descriptor used to talk to zookeeper socket句柄 | int */
    public $fd = 0;
    /* the hostname of zookeeper | string */
    public $hostname = '';
    /* the addresses that correspond to the hostname | sockaddr_storage */
    public $addrs;                  //array new sockaddr_storage;
    /* The number of addresses in the addrs array | int */
    public $addrs_count = 0;
    /* the registered watcher | watcher_fn */
    public $watcher;
    /* The time that the last message was received | timeval */
    public $last_recv;              //new timeval();
    /* The time that the last message was sent | timeval */
    public $last_send;              //new timeval();
    /* The time that the last PING was sent | timeval */
    public $last_ping;              //new timeval();
    /* The time of the next deadline | timeval */
    public $next_deadline;          //new timeval();
    /* The maximum amount of time that can go by without receiving anything from the zookeeper server | int */
    public $recv_timeout = 0;
    /* the current buffer being read in | buffer_list_t[] */
    public $input_buffer = [];
    /* The buffers that have been read and are ready to be processed. | buffer_head_t */
    public $to_process;             //new buffer_head_t();
    /* The packets queued to send | buffer_head_t */
    public $to_send;                //new buffer_head_t();
    /* The outstanding requests | completion_head_t */
    public $sent_requests;          //new completion_head_t();
    /* completions that are ready to run | completion_head_t */
    public $completions_to_process; //new completion_head_t();
    /* The index of the address to connect to | int */
    public $connect_index = 0;
    /* | clientid_t */
    public $client_id;              //new clientid_t();
    /* | long long */
    public $last_zxid = 0;
    /* Number of outstanding synchronous requests | int */
    public $outstanding_sync = 0;
    /* The buffer used for the handshake at the start of a connection | _buffer_list */
    public $primer_buffer;          //new _buffer_list();
    /* the connect response | prime_struct */
    public $primer_storage;         //new prime_struct();
    /* string 40 */
    public $primer_storage_buffer = '';
    /* int */
    public $state = 0;
    /* void * */
    public $context;
    /* authentication data list | auth_list_head_t */
    public $auth_h;                 //new auth_list_head_t();
    /* zookeeper_close is not reentrant because it de-allocates the zhandler. 
     * This guard variable is used to defer the destruction of zhandle till 
     * right before top-level API call returns to the caller
     */
    /* int32_t */
    public $ref_counter = 0;
    /* int */
    public $close_requested = 0;
    /* void * */
    public $adaptor_priv;
    /* Used for debugging only: non-zero value indicates the time when the zookeeper_process
     * call returned while there was at least one unprocessed server response 
     * available in the socket recv buffer
     */
    /* timeval */
    public $socket_readable;        //new timeval();
    /* zk_hashtable array */
    public $active_node_watchers;
    /* zk_hashtable array */
    public $active_exist_watchers;
    /* zk_hashtable array */
    public $active_child_watchers;
    /* string */
    public $chroot = '';
    function __construct(){
        $this->last_recv = new timeval();
        $this->last_send = new timeval();
        $this->last_ping = new timeval();
        $this->next_deadline = new timeval();
        $this->to_process = new buffer_head_t();
        $this->to_send = new buffer_head_t();
        $this->sent_requests = new completion_head_t();
        $this->completions_to_process = new completion_head_t();
        $this->client_id = new clientid_t();
        $this->primer_buffer = new buffer_list_t();
        $this->primer_storage = new prime_struct();
        $this->auth_h = new auth_list_head_t();
        $this->socket_readable = new timeval();
        $this->active_node_watchers = new zk_hashtable();
        $this->active_exist_watchers = new zk_hashtable();
        $this->active_child_watchers = new zk_hashtable();
    }
}
class zhandle_t extends _zhandle{
}
abstract class _auth_completion_list extends \SplStack
{
    public $completion;         //void_completion_t
    public $auth_data;          //string
    public $next;               //_auth_completion_list
    public function __construct(void_completion_t $completion = null, string $auth_data = '')
    {
        $this->completion = $completion;
        $this->auth_data = $auth_data;
        $this->next = null;
    }
}
class auth_completion_list_t extends _auth_completion_list{
}
class timeval
{
    public $tv_sec;             //long Seconds
    public $tv_usec;            //long microsecond *1000000

    public function __construct()
    {
        $this->getNow();
    }
    public function getNow(){
        $timezone = date_default_timezone_get();
        $tv = \microtime();
        $tv = \explode(' ', $tv);
        $this->tv_sec = $tv[1];
        $this->tv_usec = $tv[0] * 1000000;
        date_default_timezone_set($timezone);
    }
}
function gettimeofday(timeval &$tp, $tzp ) : int
{
    $tv = new timeval();
    $tp = &$tv;
    return 0;
}
class pollfd{
    public $fd = 0;
    public $events = 0;
    public $revents = 0;
}
class Buffer
{
    public $len;                //int32
    public $buff;               //string

    public function __construct(int $len = 0, string $buff = '')
    {
        $this->len = $len;
        $this->buff = $buff;
    }
}
class Buff_Struct
{
    public $len;                //int32
    public $off;                //int32
    public $buffer;             //string

    public function __construct(int $len = 0, int $off = 0, string $buffer = '')
    {
        $this->len = $len;
        $this->off = $off;
        $this->buffer = $buffer;
    }
}
class Id
{
    public $scheme;             //string
    public $id;                 //string

    public function __construct( string $scheme = '', string $id = '')
    {
        $this->scheme = $scheme;
        $this->id = $id;
    }
}
class ACL
{
    public $perms;              //int32
    public $id;                 //obj Id

    public function __construct( int $perms = 0, Id $id = null)
    {
        $this->perms = $perms;
        $this->id = $id ?: new Id();
    }
}
class Stat
{
    public $czxid;              //int64
    public $mzxid;              //int64
    public $ctime;              //int64
    public $mtime;              //int64
    public $version;            //int32
    public $cversion;           //int32
    public $aversion;           //int32
    public $ephemeralOwner;     //int64
    public $dataLength;         //int32
    public $numChildren;        //int32
    public $pzxid;              //int64
    
    public function __construct(int $czxid = 0, int $mzxid = 0, int $ctime = 0, int $mtime = 0, int $version = 0, int $cversion = 0, int $aversion = 0, int $ephemeralOwner = 0, int $dataLength = 0, int $numChildren = 0, int $pzxid = 0){
        $this->czxid = $czxid;
        $this->mzxid = $mzxid;
        $this->ctime = $ctime;
        $this->mtime = $mtime;
        $this->version = $version;
        $this->cversion = $cversion;
        $this->aversion = $aversion;
        $this->ephemeralOwner = $ephemeralOwner;
        $this->dataLength = $dataLength;
        $this->numChildren = $numChildren;
        $this->pzxid = $pzxid;
    }
}
class StatPersisted
{
    public $czxid;              //int64
    public $mzxid;              //int64
    public $ctime;              //int64
    public $mtime;              //int64
    public $version;            //int32
    public $cversion;           //int32
    public $aversion;           //int32
    public $ephemeralOwner;     //int64
    public $pzxid;              //int64
    
    public function __construct(int $czxid = 0, int $mzxid = 0, int $ctime = 0, int $mtime = 0, int $version = 0, int $cversion = 0, int $aversion = 0, int $ephemeralOwner = 0, int $pzxid = 0){
        $this->czxid = $czxid;
        $this->mzxid = $mzxid;
        $this->ctime = $ctime;
        $this->mtime = $mtime;
        $this->version = $version;
        $this->cversion = $cversion;
        $this->aversion = $aversion;
        $this->ephemeralOwner = $ephemeralOwner;
        $this->pzxid = $pzxid;
    }
}
class StatPersistedV1
{
    public $czxid;              //int64
    public $mzxid;              //int64
    public $ctime;              //int64
    public $mtime;              //int64
    public $version;            //int32
    public $cversion;           //int32
    public $aversion;           //int32
    public $ephemeralOwner;     //int64
    
    public function __construct(int $czxid = 0, int $mzxid = 0, int $ctime = 0, int $mtime = 0, int $version = 0, int $cversion = 0, int $aversion = 0, int $ephemeralOwner = 0){
        $this->czxid = $czxid;
        $this->mzxid = $mzxid;
        $this->ctime = $ctime;
        $this->mtime = $mtime;
        $this->version = $version;
        $this->cversion = $cversion;
        $this->aversion = $aversion;
        $this->ephemeralOwner = $ephemeralOwner;
    }
}

class ConnectRequest
{
    public $protocolVersion;    //int64
    public $lastZxidSeen;       //int64
    public $timeOut;            //int64
    public $sessionId;          //int64
    public $passwd;             //obj buffer

    public function __construct(int $protocolVersion = 0, int $lastZxidSeen = 0, int $timeOut = 0, int $sessionId = 0, Buffer $passwd = null)
    {
        $this->protocolVersion = $protocolVersion;
        $this->lastZxidSeen = $lastZxidSeen;
        $this->timeOut = $timeOut;
        $this->sessionId = $sessionId;
        $this->passwd = $passwd;
    }
}
class ConnectResponse
{
    public $protocolVersion;    //int32
    public $timeOut;            //int32
    public $sessionId;          //int64
    public $passwd;             //obj buffer

    public function __construct(int $protocolVersion = 0, int $timeOut = 0, int $sessionId = 0, Buffer $passwd = null)
    {
        $this->protocolVersion = $protocolVersion;
        $this->timeOut = $timeOut;
        $this->sessionId = $sessionId;
        $this->passwd = $passwd;
    }
}
class String_vector
{
    public $count;              //int32
    public $data;               //string

    public function __construct(int $count = 0, string $data = '')
    {
        $this->count = $count;
        $this->data = $data;
    }
}
class SetWatches
{
    public $relativeZxid;       //string
    public $dataWatches;        //obj String_vector
    public $existWatches;       //obj String_vector
    public $childWatches;       //obj String_vector

    public function __construct(string $relativeZxid = '', String_vector $dataWatches = null, String_vector $existWatches = null, String_vector $childWatches = null){
        $this->relativeZxid = $relativeZxid;
        $this->dataWatches  = $dataWatches;
        $this->existWatches = $existWatches;
        $this->childWatches = $childWatches;
    }
}
class RequestHeader
{
    public $xid = -1;           //int32
    public $type;               //int32
    
    public function __construct(int $xid = -1, int $type = 0){
        $this->xid = $xid;
        $this->type = $type;
    }
}
class MultiHeader
{
    public $type;               //int32
    public $done;               //int32
    public $err;                //int32
    
    public function __construct(int $type = 0, int $done = 0, int $err = 0){
        $this->type = $type;
        $this->done = $done;
        $this->err = $err;
    }
}
class AuthPacket
{
    public $type;               //int32
    public $scheme;             //string
    public $auth;               //obj buffer

    public function __construct(int $type = 0, string $scheme = '', Buffer $auth = null)
    {
        $this->type = $type;
        $this->scheme = $scheme;
        $this->auth = $auth;
    }
}
class ReplyHeader
{
    public $xid = -1;           //int32
    public $zxid;               //int64
    public $err;                //int32
    public function __construct(int $xid = -1, int $zxid = 0, int $err = 0)
    {
        $this->xid = $xid;
        $this->zxid = $zxid;
        $this->err = $err;
    }
}
class GetDataRequest
{
    public $path;               //string
    public $watch;              //int32
    
    public function __construct(string $path = '', int $watch = 0){
        $this->path = $path;
        $this->watch = $watch;
    }
}
class SetDataRequest
{
    public $path;               //string
    public $data;               //obj buffer
    public $version;            //int32

    public function __construct(string $path = '', Buffer $data = null, int $version = 0){
        $this->path = $path;
        $this->data = $data;
        $this->version = $version;
    }
}
class SetDataResponse
{
    public $stat;               //obj Stat

    public function __construct(Stat $stat = null)
    {
        $this->stat = $stat;
    }
}
class GetSASLRequest
{
    public $token;              //obj buffer

    public function __construct(Buffer $token = null)
    {
        $this->token = $token;
    }
}
class SetSASLRequest
{
    public $token;              //obj buffer

    public function __construct(Buffer $token = null)
    {
        $this->token = $token;
    }
}
class SetSASLResponse
{
    public $token;              //obj buffer

    public function __construct(Buffer $token = null)
    {
        $this->token = $token;
    }
}
class ACL_vector
{
    public $count;              //int32
    public $data;               //obj ACL

    public function __construct(int $count = 0, ACL $data = null)
    {
        $this->count = $count;
        $this->data = $data ?: new ACL();
    }
}
class CreateRequest
{
    public $path;               //string
    public $data;               //obj buffer
    public $acl;                //obj ACL_vector
    public $flags;              //int32

    public function __construct(string $path = '', Buffer $data = null, ACL_vector $acl = null, int $flags = 0)
    {
        $this->path = $path;
        $this->data = $data;
        $this->acl = $acl;
        $this->flags = $flags;
    }
}
class DeleteRequest
{
    public $path;               //string
    public $version;            //int32
    
    public function __construct(string $path = '', int $version = 0){
        $this->path = $path;
        $this->version = $version;
    }
}
class GetChildrenRequest
{
    public $path;               //string
    public $watch;              //int32
    
    public function __construct(string $path = '', int $watch = 0){
        $this->path = $path;
        $this->watch = $watch;
    }
}
class GetChildren2Request
{
    public $path;               //string
    public $watch;              //int32
    
    public function __construct(string $path = '', int $watch = 0){
        $this->path = $path;
        $this->watch = $watch;
    }
}
class CheckVersionRequest
{
    public $path;               //string
    public $version;            //int32
    
    public function __construct(string $path = '', int $version = 0){
        $this->path = $path;
        $this->version = $version;
    }
}
class GetMaxChildrenRequest
{
    public $path;               //string
    
    public function __construct(string $path = ''){
        $this->path = $path;
    }
}
class GetMaxChildrenResponse
{
    public $max;                //int32
    
    public function __construct(int $max = 0){
        $this->max = $max;
    }
}
class SetMaxChildrenRequest
{
    public $path;               //string
    public $max;                //int32
    
    public function __construct(string $path = '', int $max = 0){
        $this->path = $path;
        $this->max = $max;
    }
}
class SyncRequest
{
    public $path;               //string
    
    public function __construct(string $path = ''){
        $this->path = $path;
    }
}
class SyncResponse
{
    public $path;               //string
    
    public function __construct(string $path = ''){
        $this->path = $path;
    }
}
class GetACLRequest
{
    public $path;               //string
    
    public function __construct(string $path = ''){
        $this->path = $path;
    }
}
class SetACLRequest
{
    public $path;               //string
    public $acl;                //ACL_vector
    public $version;            //int32
    
    public function __construct(string $path = '', ACL_vector $acl = null, int $version = 0){
        $this->path = $path;
        $this->acl = $acl;
        $this->version = $version;
    }
}
class SetACLResponse
{
    public $stat;               //obj Stat

    public function __construct(Stat $stat = null)
    {
        $this->stat = $stat;
    }
}
class WatcherEvent
{
    public $type;               //int32
    public $state;              //int32
    public $path;               //string
    public function __construct(int $type = 0, int $state = 0, string $path = '')
    {
        $this->type = $type;
        $this->state = $state;
        $this->path = $path;
    }
}
class ErrorResponse
{
    public $err;                //int32
    
    public function __construct(int $err = 0){
        $this->err = $err;
    }
}
class CreateResponse
{
    public $path;               //string
    
    public function __construct(string $path = ''){
        $this->path = $path;
    }
}
class ExistsRequest
{
    public $path;               //string
    public $watch;              //int32
    
    public function __construct(string $path = '', int $watch = 0){
        $this->path = $path;
        $this->watch = $watch;
    }
}
class ExistsResponse
{
    public $stat;               //obj Stat

    public function __construct(Stat $stat = null)
    {
        $this->stat = $stat;
    }
}
class GetDataResponse
{
    public $data;               //obj buffer
    public $stat;               //obj Stat

    public function __construct(Buffer $bata = null, Stat $stat = null)
    {
        $this->data = $data;
        $this->stat = $stat;
    }
}
class GetChildrenResponse
{
    public $children;           //obj String_vector

    public function __construct(String_vector $children = null)
    {
        $this->children = $children;
    }
}
class GetChildren2Response
{
    public $children;           //obj String_vector
    public $stat;               //obj Stat

    public function __construct(String_vector $children = null, Stat $stat = null)
    {
        $this->children = $children;
        $this->stat = $stat;
    }
}
class GetACLResponse
{
    public $acl;                //obj ACL_vector
    public $stat;               //obj Stat

    public function __construct(ACL_vector $acl = null, Stat $stat = null)
    {
        $this->acl = $acl;
        $this->stat = $stat;
    }
}
class LearnerInfo
{
    public $serverid;           //int64
    public $protocolVersion;    //int32
    
    public function __construct(int $serverid = 0, int $protocolVersion = 0){
        $this->serverid = $serverid;
        $this->protocolVersion = $protocolVersion;
    }
}
class Id_vector
{
    public $count;              //int32
    public $data;               //obj Id

    public function __construct(int $count = 0, Id $data = null)
    {
        $this->count = $count;
        $this->data = $data;
    }
}
class QuorumPacket
{
    public $type;               //int32
    public $zxid;               //int64
    public $data;               //obj buffer
    public $authinfo;           //obj Id_vector

    public function __construct(int $type = 0, int $zxid = 0, Buffer $data = null, Id_vector $authinfo = null)
    {
        $this->type = $type;
        $this->zxid = $zxid;
        $this->data = $data;
        $this->authinfo = $authinfo;
    }
}
class QuorumAuthPacket
{
    public $magic;              //int64
    public $status;             //int32
    public $token;              //obj buffer

    public function __construct(int $magic = 0, int $status = 0, Buffer $token = null)
    {
        $this->magic = $magic;
        $this->status = $status;
        $this->token = $token;
    }
}
class FileHeader
{
    public $magic;              //int32
    public $version;            //int32
    public $dbid;               //int64
    
    public function __construct(int $magic = 0, int $version = 0, int $dbid = 0){
        $this->magic = $magic;
        $this->version = $version;
        $this->dbid = $dbid;
    }
}
class TxnHeader
{
    public $clientId;           //int64
    public $cxid;               //int32
    public $zxid;               //int64
    public $time;               //int64
    public $type;               //int32
    
    public function __construct(int $clientId = 0, int $cxid = 0, int $zxid = 0, int $time = 0, int $type = 0){
        $this->clientId = $clientId;
        $this->cxid = $cxid;
        $this->zxid = $zxid;
        $this->time = $time;
        $this->type = $type;
    }
}
class CreateTxnV0
{
    public $path;               //string
    public $data;               //obj buffer
    public $acl;                //obj ACL_vector
    public $ephemeral;          //int32

    public function __construct(string $path = '', Buffer $data = null, ACL_vector $acl = null, int $ephemeral = 0)
    {
        $this->path = $path;
        $this->data = $data;
        $this->acl = $acl;
        $this->ephemeral = $ephemeral;
    }
}
class CreateTxn
{
    public $path;               //string
    public $data;               //obj buffer
    public $acl;                //obj ACL_vector
    public $ephemeral;          //int32
    public $parentCVersion;     //int32

    public function __construct(string $path = '', Buffer $data = null, ACL_vector $acl = null, int $ephemeral = 0, int $parentCVersion = 0)
    {
        $this->path = $path;
        $this->data = $data;
        $this->acl = $acl;
        $this->ephemeral = $ephemeral;
        $this->parentCVersion = $parentCVersion;
    }
}
class DeleteTxn
{
    public $path;               //string
    
    public function __construct(string $path = ''){
        $this->path = $path;
    }
}
class SetDataTxn
{
    public $path;               //string
    public $data;               //obj buffer
    public $version;            //int32

    public function __construct(string $path = '', Buffer $data = null, int $version = 0)
    {
        $this->path = $path;
        $this->data = $data;
        $this->version = $version;
    }
}
class CheckVersionTxn
{
    public $path;               //string
    public $version;            //int32
    
    public function __construct(string $path = '', int $version = 0)
    {
        $this->path = $path;
        $this->version = $version;
    }
}
class SetACLTxn
{
    public $path;               //string
    public $acl;                //obj ACL_vector
    public $version;            //int32

    public function __construct(string $path = '', ACL_vector $acl = null, int $version = 0)
    {
        $this->path = $path;
        $this->acl = $acl;
        $this->version = $version;
    }
}
class SetMaxChildrenTxn
{
    public $path;               //string
    public $max;                //int32
    
    public function __construct(string $path = '', int $max = 0)
    {
        $this->path = $path;
        $this->max = $max;
    }
}
class CreateSessionTxn
{
    public $timeOut;            //int32
    
    public function __construct(int $timeOut = 0){
        $this->timeOut = $timeOut;
    }
}
class ErrorTxn
{
    public $err;                //int32
    
    public function __construct(int $err = 0){
        $this->err = $err;
    }
}
class Txn
{
    public $type;               //int32
    public $data;               //obj buffer

    public function __construct(int $type = 0, Buffer $data = null)
    {
        $this->type = $type;
        $this->data = $data;
    }
}
class Txn_vector
{
    public $count;              //int32
    public $data;               //obj Txn

    public function __construct(int $count = 0, Txn $data = null)
    {
        $this->count = $count;
        $this->data = $data;
    }
}
class MultiTxn
{
    public $txns;               //obj Txn_vector

    public function __construct(Txn_vector $txns = null)
    {
        $this->txns = $txns;
    }
}
function deallocate_String(string &$s)
{
    if ($s) {
        $s = null;
    }
}
function deallocate_Buffer(Buffer &$b)
{
    if ($b->buff) {
        $b->buff = null;
    }
}
function deallocate_vector(&$d)
{
}
function zoo_htonll(long $v) : string
{
    return unpack('Jcount', $v);
}
function create_buffer_iarchive(string $buffer, int $len) : iarchive
{
    $ia = new iarchive();
    $buff = new Buff_Struct();
    $buff->off = 0;
    $buff->buffer = $buffer;
    $buff->len = $len;
    $ia->priv = $buff;

    return $ia;
}
function create_buffer_oarchive() : oarchive
{
    $oa = new oarchive();
    $buff = new Buff_Struct();
    $buff->len = 128;
    $oa->priv = $buff;

    return $oa;
}
function close_buffer_iarchive(iarchive &$ia)
{
    $ia = null;
}
function close_buffer_oarchive(oarchive &$oa, bool $free_buffer)
{
    $oa->priv = null;
    $oa = null;
}
function get_buffer(oarchive &$oa) : string
{
    return $oa->priv->buffer;
}
function get_buffer_len(oarchive &$oa) : int
{
    return $oa->priv->off;
}
function resize_buffer(buff_struct &$s, int $newlen) : int
{
    if ($s->len < $newlen) {
        $s->len *= 2;
    }

    return 0;
}
function deallocate_Id(Id &$v)
{
    deallocate_String($v->scheme);
    deallocate_String($v->id);
}

/* auth_completion_list_t array &$a_list[] */
function get_last_auth(array &$auth_list) : auth_info
{
    $element = & end($auth_list);
    return $element;
}
/* auth_completion_list_t array &$a_list[] */
function free_auth_completion(auth_completion_list_t &$a_list){
    $a_list = null;
    return;
}
/* auth_completion_list_t array &$a_list[] */
function add_auth_completion(array &$a_list, void_completion_t &$completion, string $data){
    $element = new auth_completion_list_t($completion, $data);
    $a_list[] = $element;
    return ;
}
/* auth_list_head_t &$auth_list $auth_list->auth = auth_info[], auth_completion_list_t array &$a_list[] */
function get_auth_completions(auth_list_head_t &$auth_list, auth_completion_list_t &$a_list){
    $element = $auth_list->auth;
    if(!$element || !is_array($element)){
        return;
    }
    while($element){
        if($element->completion){
            add_auth_completion($a_list, $element->completion, $element->data);
        }
        $element->completion = NULL;
        $element = $element->next();
    }
    unset($element);
    return ;
}
/* auth_list_head_t &$auth_list $auth_list->auth = auth_info[], auth_info &add_el */
function add_last_auth(auth_list_head_t &$auth_list, auth_info &$add_el) {
    $auth_list->auth[] = $add_el;
    return;
}
function init_auth_info(auth_list_head_t &$auth_list)
{
    $auth_list->auth = NULL;
}
function mark_active_auth(zhandle_t &$zh) {
    $auth_h = $zh->auth_h;
    if (!$auth_h->auth || !is_array($element)) {
        return;
    }
    $element = $auth_h->auth;
    foreach($element as &$e){
        $e->state = 1;
    }
    unset($e);
}
function free_auth_info(auth_list_head_t &$auth_list){
    init_auth_info($auth_list);
}
function is_unrecoverable(zhandle_t &$zh) : int
{
    return ($zh->state<0) ? ZINVALIDSTATE : ZOK;
}
function exists_result_checker(zhandle_t &$zh, int $rc) : zk_hashtable
{
    if ($rc == ZOK) {
        return $zh->active_node_watchers;
    } else if ($rc == ZNONODE) {
        return $zh->active_exist_watchers;
    }
    return 0;
}
function data_result_checker(zhandle_t &$zh, int $rc) : zk_hashtable
{
    return $rc==ZOK ? $zh->active_node_watchers : 0;
}
function child_result_checker(zhandle_t &$zh, int $rc) : zk_hashtable
{
    return $rc==ZOK ? $zh->active_child_watchers : 0;
}



/****************************************
* 序列化 和反序列化 工具底层的实现，
* 全部使用静态类来实现。
*/
class iarchive
{
    public $priv;               //Buff_struct

    public function __construct()
    {
        $this->priv = new Buff_Struct();
    }

    /************************************************************
    * 开始记录
    *
    * @param iarchive $ia
    * @param string $tag
    */
    public function start_record(self &$ia, string $tag)
    {
        return 0;
    }

    /************************************************************
    * 结束记录
    *
    * @param iarchive $ia
    * @param string $tag
    */
    public function end_record(self &$ia, string $tag)
    {
        return 0;
    }

    /************************************************************
    * 开始矢量
    *
    * @param iarchive $ia
    * @param string $tag
    * @param int 32 $count
    */
    public function start_vector(self &$ia, string $tag, int &$count)
    {
        return $this->deserialize_Int($ia, $tag, $count);
    }

    /************************************************************
    * 结束矢量
    *
    * @param iarchive $ia
    * @param string $tag
    */
    public function end_vector(self &$ia, string $tag)
    {
        return 0;
    }

    /************************************************************
    * 反序列化Bool
    *
    * @param iarchive $ia
    * @param string $tag
    * @param int 32 $v
    */
    public function deserialize_Bool(self &$ia, string $tag, int &$v)
    {
        $priv = $ia->priv;
        if ($priv->len - $priv->off < BOOL) {
            return -E2BIG;
        }
        $v = substr($priv->buffer, $priv->off, BOOL);
        $priv->off += 1;
        $v = unpack('Cv', $v);
        $v = $v['v'];

        return 0;
    }

    /************************************************************
    * 反序列化Int
    *
    * @param iarchive $ia
    * @param string $tag
    * @param int 32 $count
    */
    public function deserialize_Int(self &$ia, string $tag, int &$count)
    {
        $priv = $ia->priv;
        if ($priv->len - $priv->off < INT32_T) {
            return -E2BIG;
        }
        $count = substr($priv->buffer, $priv->off, INT32_T);
        //int32_t长度是4
        $priv->off += INT32_T;
        $count = unpack('Ncount', $count);
        $count = $count['count'];

        return 0;
    }

    /************************************************************
    * 反序列化Long
    *
    * @param iarchive $ia
    * @param string $tag
    * @param int 64 $count
    */
    public function deserialize_Long(self &$ia, string $tag, int &$count)
    {
        $priv = $ia->priv;
        $v = 0;
        if ($priv->len - $priv->off < INT64_T) {
            return -E2BIG;
        }
        $count = substr($priv->buffer, $priv, INT64_T);
        //int64_t长度是8
        $priv->off += INT64_T;
//        $v = unpack("Jcount", $count);
        $v = zoo_htonll($$count);
        $count = $v;

        return 0;
    }

    /************************************************************
    * 反序列化Buffer
    *
    * @param iarchive $ia
    * @param string $tag
    * @param Buffer $b
    */
    public function deserialize_Buffer(self &$ia, string $tag, Buffer $b)
    {
        $priv = $ia->priv;
        $rc = $this->deserialize_Int($ia, 'len', $b->len);
        if ($rc < 0) {
            return $rc;
        }
        if ($priv->len - $priv->off < $b->len) {
            return -E2BIG;
        }
        if ($b->len == -1) {
            $b->buff = null;

            return $rc;
        }
        $b->buff = substr($priv->buffer, $priv->off, $b->len);
        $priv->off += $b->len;

        return 0;
    }

    /************************************************************
    * 反序列化String
    *
    * @param iarchive $ia
    * @param string $tag
    * @param string $s
    */
    public function deserialize_String(self &$ia, string $tag, string &$s)
    {
        $priv = $ia->priv;
        $len = 0;
        $rc = $this->deserialize_Int($ia, 'len', $len);
        if ($rc < 0) {
            return $rc;
        }
        if ($priv->len - $priv->off < $len) {
            return -E2BIG;
        }
        if ($len < 0) {
            return -EINVAL;
        }
        $s = substr($priv->buffer, $priv->off, $len);
        $priv->off += $len;

        return 0;
    }
}/****************************************
* 序列化 和反序列化 工具底层的实现，
* 全部使用静态类来实现。
*/
class oarchive
{
    public $priv;               //Buff_struct

    public function __construct()
    {
        $this->priv = new Buff_Struct();
    }

    /************************************************************
    * 开始记录
    *
    * @param oarchive $oa
    * @param string $tag
    */
    public function start_record(self &$oa, string $tag)
    {
        return 0;
    }

    /************************************************************
    * 结束记录
    *
    * @param oarchive $oa
    * @param string $tag
    */
    public function end_record(self &$oa, string $tag)
    {
        return 0;
    }

    /************************************************************
    * 开始矢量
    *
    * @param oarchive $oa
    * @param string $tag
    * @param int 32 $count
    */
    public function start_vector(self &$oa, string $tag, int &$count)
    {
        return $this->serialize_Int($oa, $tag, $count);
    }

    /************************************************************
    * 结束矢量
    *
    * @param oarchive $oa
    * @param string $tag
    */
    public function end_vector(self &$oa, string $tag)
    {
        return 0;
    }

    /************************************************************
    * 序列化Bool
    *
    * @param oarchive $oa
    * @param string $tag
    * @param int 32 $v
    */
    public function serialize_Bool(self &$oa, string $tag, int &$v)
    {
        $priv = $oa->priv;
        if ($priv->len - $priv->off < BOOL) {
            $rc = resize_buffer($priv, $priv->len + BOOL);
            if ($rc < 0) {
                return $rc;
            }
        }
        $priv->buffer .= $v == 0 ? "\000" : "\001";
        ++$priv->off;

        return 0;
    }

    /************************************************************
    * 序列化Int
    *
    * @param oarchive $oa
    * @param string $tag
    * @param int 32 $d
    */
    public function serialize_Int(self &$oa, string $tag, int $d)
    {
        $rc = 0;
        $priv = $oa->priv;
        $i = pack('N', $d);
        if ($priv->len - $priv->off < INT32_T) {
            $rc = resize_buffer($priv, $priv->len + INT32_T);
            if ($rc < 0) {
                return $rc;
            }
        }
        $priv->buffer .= $i;
        $priv->off += INT32_T;

        return $rc;
    }

    /************************************************************
    * 序列化Long
    *
    * @param oarchive $oa
    * @param string $tag
    * @param int 64 $d
    */
    public function serialize_Long(self &$oa, string $tag, int $d)
    {
        $i = pack('J', $d);
        $priv = $oa->priv;
        if ($priv->len - $priv->off < INT32_T) {
            $rc = resize_buffer($priv, $priv->len + INT32_T);
            if ($rc < 0) {
                return $rc;
            }
        }
        $priv->buffer .= $i;
        $priv->off += INT32_T;

        return $rc;
    }

    /************************************************************
    * 序列化Buffer
    *
    * @param oarchive $oa
    * @param string $tag
    * @param Buffer $b
    */
    public function serialize_Buffer(self &$oa, string $tag, Buffer &$b)
    {
        $priv = $oa->priv;
        if (!$b) {
            return $this->serialize_Int($oa, 'len', -1);
        }
        $rc = $this->serialize_Int($oa, 'len', $b->len);
        if ($rc < 0) {
            return $rc;
        }
        if ($b->len == -1) {
            return $rc;
        }
        if ($priv->len - $priv->off < $b->len) {
            $rc = resize_buffer($priv, $priv->len + $b->len);
            if ($rc < 0) {
                return $rc;
            }
        }
        $priv->buffer .= $b->len;
        $priv->off += $b->len;

        return $rc;
    }

    /************************************************************
    * 序列化String
    *
    * @param oarchive $oa
    * @param string $tag
    * @param string $s
    */
    public function serialize_String(self &$oa, string $tag, string &$s)
    {
        $priv = $oa->priv;
        if (!$s) {
            $this->serialize_Int($oa, 'len', -1);

            return 0;
        }
        $len = strlen($s);
        $rc = $this->serialize_Int($oa, 'len', $len);
        if ($rc < 0) {
            return $rc;
        }
        if ($priv->len - $priv->off < $len) {
            $rc = resize_buffer($priv, $priv->len + $len);
            if ($rc < 0) {
                return $rc;
            }
        }
        $priv->buffer .= $s;
        $priv->off += $len;

        return $rc;
    }
}
class recordio
{
}
/****************************************
* 序列化 和反序列化 工具，
* 不与数据结构混合模式
*/
class Serialize
{
    /******************************************
    * 序列化 Id
    *
    * @param oarchive $out
    * @param string $tag
    * @param Id $v
    */
    public function serialize_Id(oarchive &$out, string $tag, Id &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'scheme', $v->scheme);
        $rc = $rc ? $rc : $out->serialize_String($out, 'id', $v->id);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 Id
    *
    * @param iarchive $in
    * @param string $tag
    * @param Id $v
    */
    public function deserialize_Id(iarchive &$in, string $tag, Id &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'scheme', $v->scheme);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'id', $v->id);
        $rc = $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 Id
    *
    * @param Id $v
    */
    public function deallocate_Id(Id &$v)
    {
        deallocate_String($v->scheme);
        deallocate_String($v->id);
    }

    /******************************************
    * 序列化 ACL
    *
    * @param oarchive $out
    * @param string $tag
    * @param ACL $v
    */
    public function serialize_ACL(oarchive &$out, string $tag, ACL $v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'perms', $v->perms);
        $rc = $rc ? $rc : self::serialize_Id($out, 'id', $v->id);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 ACL
    *
    * @param iarchive $in
    * @param string $tag
    * @param ACL $v
    */
    public function deserialize_ACL(iarchive &$in, string $tag, ACL $v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'perms', $v->perms);
        $rc = $rc ? $rc : self::deserialize_Id($in, 'id', $v->id);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 ACL
    *
    * @param ACL $v
    */
    public function deallocate_ACL(ACL &$v)
    {
        deallocate_Id($v->id);
    }

    /******************************************
    * 序列化 Stat
    *
    * @param oarchive $out
    * @param string $tag
    * @param Stat $v
    */
    public function serialize_Stat(oarchive &$out, string $tag, Stat &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'czxid', $v->czxid);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'mzxid', $v->mzxid);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'ctime', $v->ctime);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'mtime', $v->mtime);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'version', $v->version);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'cversion', $v->cversion);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'aversion', $v->aversion);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'ephemeralOwner', $v->ephemeralOwner);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'dataLength', $v->dataLength);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'numChildren', $v->numChildren);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'pzxid', $v->pzxid);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 Stat
    *
    * @param iarchive $in
    * @param string $tag
    * @param Stat $v
    */
    public function deserialize_Stat(iarchive $in, string $tag, Stat &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'czxid', $v->czxid);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'mzxid', $v->mzxid);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'ctime', $v->ctime);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'mtime', $v->mtime);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'version', $v->version);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'cversion', $v->cversion);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'aversion', $v->aversion);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'ephemeralOwner', $v->ephemeralOwner);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'dataLength', $v->dataLength);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'numChildren', $v->numChildren);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'pzxid', $v->pzxid);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 Stat
    *
    * @param Stat $v
    */
    public function deallocate_Stat(Stat &$v)
    {
    }

    /******************************************
    * 序列化 StatPersisted
    *
    * @param oarchive $out
    * @param string $tag
    * @param StatPersisted $v
    */
    public function serialize_StatPersisted(oarchive &$out, string $tag, StatPersisted &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'czxid', $v->czxid);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'mzxid', $v->mzxid);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'ctime', $v->ctime);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'mtime', $v->mtime);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'version', $v->version);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'cversion', $v->cversion);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'aversion', $v->aversion);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'ephemeralOwner', $v->ephemeralOwner);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'pzxid', $v->pzxid);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 StatPersisted
    *
    * @param iarchive $in
    * @param string $tag
    * @param StatPersisted $v
    */
    public function deserialize_StatPersisted(iarchive &$in, string $tag, StatPersisted &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'czxid', $v->czxid);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'mzxid', $v->mzxid);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'ctime', $v->ctime);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'mtime', $v->mtime);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'version', $v->version);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'cversion', $v->cversion);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'aversion', $v->aversion);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'ephemeralOwner', $v->ephemeralOwner);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'pzxid', $v->pzxid);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 StatPersisted
    *
    * @param StatPersisted $v
    */
    public function deallocate_StatPersisted(StatPersisted &$v)
    {
    }

    /******************************************
    * 序列化 StatPersistedV1
    *
    * @param oarchive $out
    * @param string $tag
    * @param StatPersistedV1 $v
    */
    public function serialize_StatPersistedV1(oarchive &$out, string $tag, StatPersistedV1 &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'czxid', $v->czxid);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'mzxid', $v->mzxid);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'ctime', $v->ctime);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'mtime', $v->mtime);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'version', $v->version);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'cversion', $v->cversion);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'aversion', $v->aversion);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'ephemeralOwner', $v->ephemeralOwner);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 StatPersistedV1
    *
    * @param iarchive $in
    * @param string $tag
    * @param StatPersistedV1 $v
    */
    public function deserialize_StatPersistedV1(iarchive &$in, string $tag, StatPersistedV1 &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'czxid', $v->czxid);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'mzxid', $v->mzxid);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'ctime', $v->ctime);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'mtime', $v->mtime);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'version', $v->version);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'cversion', $v->cversion);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'aversion', $v->aversion);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'ephemeralOwner', $v->ephemeralOwner);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 StatPersistedV1
    *
    * @param StatPersistedV1 $v
    */
    public function deallocate_StatPersistedV1(StatPersistedV1 &$v)
    {
    }

    /******************************************
    * 序列化 ConnectRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param ConnectRequest $v
    */
    public function serialize_ConnectRequest(oarchive &$out, string $tag, ConnectRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'protocolVersion', $v->protocolVersion);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'lastZxidSeen', $v->lastZxidSeen);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'timeOut', $v->timeOut);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'sessionId', $v->sessionId);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'passwd', $v->passwd);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 ConnectRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param ConnectRequest $v
    */
    public function deserialize_ConnectRequest(iarchive &$in, string $tag, ConnectRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'protocolVersion', $v->protocolVersion);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'lastZxidSeen', $v->lastZxidSeen);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'timeOut', $v->timeOut);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'sessionId', $v->sessionId);
        $rc = $rc ? $rc : $in->deserialize_Buffer($in, 'passwd', $v->passwd);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 ConnectRequest
    *
    * @param ConnectRequest $v
    */
    public function deallocate_ConnectRequest(ConnectRequest &$v)
    {
        deallocate_Buffer($v->passwd);
    }

    /******************************************
    * 序列化 ConnectResponse
    *
    * @param oarchive $out
    * @param string $tag
    * @param ConnectResponse $v
    */
    public function serialize_ConnectResponse(oarchive &$out, string $tag, ConnectRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'protocolVersion', $v->protocolVersion);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'timeOut', $v->timeOut);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'sessionId', $v->sessionId);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'passwd', $v->passwd);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 ConnectResponse
    *
    * @param iarchive $in
    * @param string $tag
    * @param ConnectResponse $v
    */
    public function deserialize_ConnectResponse(iarchive &$in, string $tag, ConnectRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'protocolVersion', $v->protocolVersion);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'timeOut', $v->timeOut);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'sessionId', $v->sessionId);
        $rc = $rc ? $rc : $in->deserialize_Buffer($in, 'passwd', $v->passwd);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 ConnectResponse
    *
    * @param ConnectResponse $v
    */
    public function deallocate_ConnectResponse(ConnectRequest &$v)
    {
        deallocate_Buffer($v->passwd);
    }

    /******************************************
    * 序列化 String_vector
    *
    * @param oarchive $out
    * @param string $tag
    * @param String_vector $v
    */
    public function serialize_String_vector(oarchive &$out, string $tag, String_vector &$v)
    {
        $rc = $out->start_vector($out, $tag, $v->count);
        $rc = $rc ? $rc : $out->serialize_String($out, 'data', $v->data);
        $rc = $rc ? $rc : $out->end_vector($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 String_vector
    *
    * @param iarchive $in
    * @param string $tag
    * @param String_vector $v
    */
    public function deserialize_String_vector(iarchive &$in, string $tag, String_vector &$v)
    {
        $rc = $in->start_vector($in, $tag, $v->count);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'value', $v->data);
        $rc = $rc ? $rc : $in->end_vector($in, $tag);

        return $rc;
    }

    /******************************************
    * 初始化分配 String_vector
    *
    * @param String_vector $v
    */
    public function allocate_String_vector(String_vector &$v, $len)
    {
        if (!$len) {
            $v->count = 0;
            $v->data = 0;
        } else {
            $v->count = $len;
            $v->data = null;
        }

        return 0;
    }

    /******************************************
    * 释放 String_vector
    *
    * @param String_vector $v
    */
    public function deallocate_String_vector(String_vector &$v)
    {
        if ($v->data) {
            deallocate_String($v->data);
//            $v->data = 0;
        }

        return 0;
    }

    /******************************************
    * 序列化 SetWatches
    *
    * @param oarchive $out
    * @param string $tag
    * @param SetWatches $v
    */
    public function serialize_SetWatches(oarchive &$out, string $tag, SetWatches &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'relativeZxid', $v->relativeZxid);
        $rc = $rc ? $rc : $this->serialize_String_vector($out, 'dataWatches', $v->dataWatches);
        $rc = $rc ? $rc : $this->serialize_String_vector($out, 'existWatches', $v->existWatches);
        $rc = $rc ? $rc : $this->serialize_String_vector($out, 'childWatches', $v->childWatches);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 SetWatches
    *
    * @param iarchive $in
    * @param string $tag
    * @param SetWatches $v
    */
    public function deserialize_SetWatches(iarchive &$in, string $tag, SetWatches &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'relativeZxid', $v->relativeZxid);
        $rc = $rc ? $rc : $this->deserialize_String_vector($in, 'dataWatches', $v->dataWatches);
        $rc = $rc ? $rc : $this->deserialize_String_vector($in, 'existWatches', $v->existWatches);
        $rc = $rc ? $rc : $this->deserialize_String_vector($in, 'childWatches', $v->childWatches);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 SetWatches
    *
    * @param SetWatches $v
    */
    public function deallocate_SetWatches(SetWatches &$v)
    {
        $this->deallocate_String_vector($v->dataWatches);
        $this->deallocate_String_vector($v->existWatches);
        $this->deallocate_String_vector($v->childWatches);
    }

    /******************************************
    * 序列化 RequestHeader
    *
    * @param oarchive $out
    * @param string $tag
    * @param RequestHeader $v
    */
    public function serialize_RequestHeader(oarchive &$out, string $tag, RequestHeader &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'xid', $v->xid);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'type', $v->type);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 RequestHeader
    *
    * @param iarchive $in
    * @param string $tag
    * @param RequestHeader $v
    */
    public function deserialize_RequestHeader(iarchive &$in, string $tag, RequestHeader &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'xid', $v->xid);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'type', $v->type);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 RequestHeader
    *
    * @param RequestHeader $v
    */
    public function deallocate_RequestHeader(RequestHeader &$v)
    {
    }

    /******************************************
    * 序列化 MultiHeader
    *
    * @param oarchive $out
    * @param string $tag
    * @param MultiHeader $v
    */
    public function serialize_MultiHeader(oarchive &$out, string $tag, MultiHeader &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'type', $v->type);
        $rc = $rc ? $rc : $out->serialize_Bool($out, 'done', $v->done);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'err', $v->err);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 MultiHeader
    *
    * @param iarchive $in
    * @param string $tag
    * @param MultiHeader $v
    */
    public function deserialize_MultiHeader(iarchive &$in, string $tag, MultiHeader &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'type', $v->type);
        $rc = $rc ? $rc : $in->deserialize_Bool($in, 'done', $v->done);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'err', $v->err);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 MultiHeader
    *
    * @param MultiHeader $v
    */
    public function deallocate_MultiHeader(MultiHeader &$v)
    {
    }

    /******************************************
    * 序列化 AuthPacket
    *
    * @param oarchive $out
    * @param string $tag
    * @param AuthPacket $v
    */
    public function serialize_AuthPacket(oarchive &$out, string $tag, AuthPacket &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'type', $v->type);
        $rc = $rc ? $rc : $out->serialize_String($out, 'scheme', $v->scheme);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'auth', $v->auth);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 AuthPacket
    *
    * @param iarchive $in
    * @param string $tag
    * @param AuthPacket $v
    */
    public function deserialize_AuthPacket(iarchive &$in, string $tag, AuthPacket &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'type', $v->type);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'scheme', $v->scheme);
        $rc = $rc ? $rc : $in->deserialize_Buffer($in, 'auth', $v->auth);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 AuthPacket
    *
    * @param AuthPacket $v
    */
    public function deallocate_AuthPacket(AuthPacket &$v)
    {
        deallocate_String($v->scheme);
        deallocate_Buffer($v->auth);
    }

    /******************************************
    * 序列化 ReplyHeader
    *
    * @param oarchive $out
    * @param string $tag
    * @param ReplyHeader $v
    */
    public function serialize_ReplyHeader(oarchive &$out, string $tag, ReplyHeader &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'xid', $v->xid);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'zxid', $v->zxid);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'err', $v->err);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 ReplyHeader
    *
    * @param iarchive $in
    * @param string $tag
    * @param ReplyHeader $v
    */
    public function deserialize_ReplyHeader(iarchive &$in, string $tag, ReplyHeader &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'xid', $v->xid);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'zxid', $v->zxid);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'err', $v->err);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 ReplyHeader
    *
    * @param ReplyHeader $v
    */
    public function deallocate_ReplyHeader(ReplyHeader &$v)
    {
    }

    /******************************************
    * 序列化 GetDataRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param GetDataRequest $v
    */
    public function serialize_GetDataRequest(oarchive &$out, string $tag, GetDataRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->serialize_Bool($out, 'watch', $v->watch);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 GetDataRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param GetDataRequest $v
    */
    public function deserialize_GetDataRequest(iarchive &$in, string $tag, GetDataRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->deserialize_Bool($in, 'watch', $v->watch);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 GetDataRequest
    *
    * @param GetDataRequest $v
    */
    public function deallocate_GetDataRequest(GetDataRequest &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 SetDataRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param SetDataRequest $v
    */
    public function serialize_SetDataRequest(oarchive &$out, string $tag, SetDataRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'data', $v->data);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'version', $v->version);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 SetDataRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param SetDataRequest $v
    */
    public function deserialize_SetDataRequest(iarchive &$in, string $tag, SetDataRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->deserialize_Buffer($in, 'data', $v->data);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'version', $v->version);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 SetDataRequest
    *
    * @param SetDataRequest $v
    */
    public function deallocate_SetDataRequest(SetDataRequest &$v)
    {
        deallocate_String($v->path);
        deallocate_Buffer($v->data);
    }

    /******************************************
    * 序列化 SetDataResponse
    *
    * @param oarchive $out
    * @param string $tag
    * @param SetDataResponse $v
    */
    public function serialize_SetDataResponse(oarchive &$out, string $tag, SetDataResponse &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $this->serialize_Stat($out, 'stat', $v->stat);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 SetDataResponse
    *
    * @param iarchive $in
    * @param string $tag
    * @param SetDataResponse $v
    */
    public function deserialize_SetDataResponse(iarchive &$in, string $tag, SetDataResponse &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $this->deserialize_Stat($in, 'stat', $v->stat);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 SetDataResponse
    *
    * @param SetDataResponse $v
    */
    public function deallocate_SetDataResponse(SetDataResponse &$v)
    {
        $rc = $rc ? $rc : $this->deallocate_Stat($in, 'stat', $v->stat);
    }

    /******************************************
    * 序列化 GetSASLRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param GetSASLRequest $v
    */
    public function serialize_GetSASLRequest(oarchive &$out, string $tag, GetSASLRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'token', $v->token);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 GetSASLRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param GetSASLRequest $v
    */
    public function deserialize_GetSASLRequest(iarchive &$in, string $tag, GetSASLRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $out->deserialize_Buffer($in, 'token', $v->token);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 GetSASLRequest
    *
    * @param GetSASLRequest $v
    */
    public function deallocate_GetSASLRequest(GetSASLRequest &$v)
    {
        deallocate_Buffer($v->token);
    }

    /******************************************
    * 序列化 SetSASLRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param SetSASLRequest $v
    */
    public function serialize_SetSASLRequest(oarchive &$out, string $tag, SetSASLRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'token', $v->token);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 SetSASLRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param SetSASLRequest $v
    */
    public function deserialize_SetSASLRequest(iarchive &$in, string $tag, SetSASLRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $out->deserialize_Buffer($in, 'token', $v->token);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 SetSASLRequest
    *
    * @param SetSASLRequest $v
    */
    public function deallocate_SetSASLRequest(SetSASLRequest &$v)
    {
        deallocate_Buffer($v->token);
    }

    /******************************************
    * 序列化 SetSASLResponse
    *
    * @param oarchive $out
    * @param string $tag
    * @param SetSASLResponse $v
    */
    public function serialize_SetSASLResponse(oarchive &$out, string $tag, SetSASLResponse &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'token', $v->token);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 SetSASLResponse
    *
    * @param iarchive $in
    * @param string $tag
    * @param SetSASLResponse $v
    */
    public function deserialize_SetSASLResponse(iarchive &$in, string $tag, SetSASLResponse &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $out->deserialize_Buffer($in, 'token', $v->token);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 SetSASLResponse
    *
    * @param SetSASLResponse $v
    */
    public function deallocate_SetSASLResponse(SetSASLResponse &$v)
    {
        deallocate_Buffer($v->token);
    }

    /******************************************
    * 序列化 ACL_vector
    *
    * @param oarchive $out
    * @param string $tag
    * @param ACL_vector $v
    */
    public function serialize_ACL_vector(oarchive &$out, string $tag, ACL_vector &$v)
    {
        $count = $v->count;
        $rc = $out->start_vector($out, $tag, $count);
        $rc = $rc ? $rc : self::serialize_ACL($out, 'data', $v->data);
        $rc = $rc ? $rc : $out->end_vector($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 ACL_vector
    *
    * @param iarchive $in
    * @param string $tag
    * @param ACL_vector $v
    */
    public function deserialize_ACL_vector(iarchive &$in, string $tag, ACL_vector &$v)
    {
        $count = $v->count;
        $rc = $in->start_vector($in, $tag, $count);
        $rc = $rc ? $rc : self::serialize_ACL($in, 'data', $v->data);
        $rc = $rc ? $rc : $in->end_vector($in, $tag);

        return $rc;
    }

    /******************************************
    * 初始化 ACL_vector
    *
    * @param int $len
    * @param ACL_vector $v
    */
    public function allocate_ACL_vector(ACL_vector &$v, int $len)
    {
        if (!$len) {
            $v->count = 0;
            $v->data = 0;
        } else {
            $v->count = $len;
            $v->data = null;
        }

        return 0;
    }

    /******************************************
    * 释放 ACL_vector
    *
    * @param ACL_vector $v
    */
    public function deallocate_ACL_vector(ACL_vector &$v)
    {
        if ($v->data) {
            $this->deallocate_ACL($v->data);
//            $v->data = null;
        }

        return 0;
    }

    /******************************************
    * 序列化 CreateRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param CreateRequest $v
    */
    public function serialize_CreateRequest(oarchive &$out, string $tag, CreateRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'data', $v->data);
        $rc = $rc ? $rc : self::serialize_ACL_vector($out, 'acl', $v->acl);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'flags', $v->flags);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 CreateRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param CreateRequest $v
    */
    public function deserialize_CreateRequest(iarchive &$in, string $tag, CreateRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->deserialize_Buffer($in, 'data', $v->data);
        $rc = $rc ? $rc : self::deserialize_ACL_vector($in, 'acl', $v->acl);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'flags', $v->flags);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 CreateRequest
    *
    * @param CreateRequest $v
    */
    public function deallocate_CreateRequest(CreateRequest &$v)
    {
        deallocate_String($v->path);
        deallocate_Buffer($v->data);
        $this->deallocate_ACL_vector($v->acl);
    }

    /******************************************
    * 序列化 DeleteRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param DeleteRequest $v
    */
    public function serialize_DeleteRequest(oarchive &$out, string $tag, DeleteRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'version', $v->version);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 DeleteRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param DeleteRequest $v
    */
    public function deserialize_DeleteRequest(iarchive &$in, string $tag, DeleteRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'version', $v->version);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 DeleteRequest
    *
    * @param DeleteRequest $v
    */
    public function deallocate_DeleteRequest(DeleteRequest &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 GetChildrenRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param GetChildrenRequest $v
    */
    public function serialize_GetChildrenRequest(oarchive &$out, string $tag, GetChildrenRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->serialize_Bool($out, 'watch', $v->watch);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 GetChildrenRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param GetChildrenRequest $v
    */
    public function deserialize_GetChildrenRequest(iarchive &$in, string $tag, GetChildrenRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->deserialize_Bool($in, 'watch', $v->watch);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 GetChildrenRequest
    *
    * @param GetChildrenRequest $v
    */
    public function deallocate_GetChildrenRequest(GetChildrenRequest &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 GetChildren2Request
    *
    * @param oarchive $out
    * @param string $tag
    * @param GetChildren2Request $v
    */
    public function serialize_GetChildren2Request(oarchive &$out, string $tag, GetChildren2Request &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->serialize_Bool($out, 'watch', $v->watch);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 GetChildren2Request
    *
    * @param iarchive $in
    * @param string $tag
    * @param GetChildren2Request $v
    */
    public function deserialize_GetChildren2Request(iarchive &$in, string $tag, GetChildren2Request &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->deserialize_Bool($in, 'watch', $v->watch);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 GetChildren2Request
    *
    * @param GetChildren2Request $v
    */
    public function deallocate_GetChildren2Request(GetChildren2Request &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 CheckVersionRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param CheckVersionRequest $v
    */
    public function serialize_CheckVersionRequest(oarchive &$out, string $tag, CheckVersionRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'version', $v->version);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 CheckVersionRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param CheckVersionRequest $v
    */
    public function deserialize_CheckVersionRequest(iarchive &$in, string $tag, CheckVersionRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'version', $v->version);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 CheckVersionRequest
    *
    * @param CheckVersionRequest $v
    */
    public function deallocate_CheckVersionRequest(CheckVersionRequest &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 GetMaxChildrenRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param GetMaxChildrenRequest $v
    */
    public function serialize_GetMaxChildrenRequest(oarchive &$out, string $tag, GetMaxChildrenRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 GetMaxChildrenRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param GetMaxChildrenRequest $v
    */
    public function deserialize_GetMaxChildrenRequest(iarchive &$in, string $tag, GetMaxChildrenRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 GetMaxChildrenRequest
    *
    * @param GetMaxChildrenRequest $v
    */
    public function deallocate_GetMaxChildrenRequest(GetMaxChildrenRequest &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 GetMaxChildrenResponse
    *
    * @param oarchive $out
    * @param string $tag
    * @param GetMaxChildrenResponse $v
    */
    public function serialize_GetMaxChildrenResponse(oarchive &$out, string $tag, GetMaxChildrenResponse &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'max', $v->max);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 GetMaxChildrenResponse
    *
    * @param iarchive $in
    * @param string $tag
    * @param GetMaxChildrenResponse $v
    */
    public function deserialize_GetMaxChildrenResponse(iarchive &$in, string $tag, GetMaxChildrenResponse &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'max', $v->max);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 GetMaxChildrenResponse
    *
    * @param GetMaxChildrenResponse $v
    */
    public function deallocate_GetMaxChildrenResponse(GetMaxChildrenResponse &$v)
    {
    }

    /******************************************
    * 序列化 SetMaxChildrenRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param SetMaxChildrenRequest $v
    */
    public function serialize_SetMaxChildrenRequest(oarchive &$out, string $tag, SetMaxChildrenRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'max', $v->max);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 SetMaxChildrenRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param SetMaxChildrenRequest $v
    */
    public function deserialize_SetMaxChildrenRequest(iarchive &$in, string $tag, SetMaxChildrenRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'max', $v->max);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 SetMaxChildrenRequest
    *
    * @param SetMaxChildrenRequest $v
    */
    public function deallocate_SetMaxChildrenRequest(SetMaxChildrenRequest &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 SyncRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param SyncRequest $v
    */
    public function serialize_SyncRequest(oarchive &$out, string $tag, SyncRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 SyncRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param SyncRequest $v
    */
    public function deserialize_SyncRequest(iarchive &$in, string $tag, SyncRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 SyncRequest
    *
    * @param SyncRequest $v
    */
    public function deallocate_SyncRequest(SyncRequest &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 SyncResponse
    *
    * @param oarchive $out
    * @param string $tag
    * @param SyncResponse $v
    */
    public function serialize_SyncResponse(oarchive &$out, string $tag, SyncResponse &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 SyncResponse
    *
    * @param iarchive $in
    * @param string $tag
    * @param SyncResponse $v
    */
    public function deserialize_SyncResponse(iarchive &$in, string $tag, SyncResponse &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 SyncResponse
    *
    * @param SyncResponse $v
    */
    public function deallocate_SyncResponse(SyncResponse &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 GetACLRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param GetACLRequest $v
    */
    public function serialize_GetACLRequest(oarchive &$out, string $tag, GetACLRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 GetACLRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param GetACLRequest $v
    */
    public function deserialize_GetACLRequest(iarchive &$in, string $tag, GetACLRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 GetACLRequest
    *
    * @param GetACLRequest $v
    */
    public function deallocate_GetACLRequest(GetACLRequest &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 SetACLRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param SetACLRequest $v
    */
    public function serialize_SetACLRequest(oarchive &$out, string $tag, SetACLRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $this->serialize_ACL_vector($out, 'acl', $v->acl);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'version', $v->version);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 SetACLRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param SetACLRequest $v
    */
    public function deserialize_SetACLRequest(iarchive &$in, string $tag, SetACLRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $this->deserialize_ACL_vector($in, 'acl', $v->acl);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'version', $v->version);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 SetACLRequest
    *
    * @param SetACLRequest $v
    */
    public function deallocate_SetACLRequest(SetACLRequest &$v)
    {
        deallocate_String($v->path);
        $this->deallocate_ACL_vector($v->acl);
    }

    /******************************************
    * 序列化 SetACLResponse
    *
    * @param oarchive $out
    * @param string $tag
    * @param SetACLResponse $v
    */
    public function serialize_SetACLResponse(oarchive &$out, string $tag, SetACLResponse &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $this->serialize_Stat($out, 'stat', $v->stat);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 SetACLResponse
    *
    * @param iarchive $in
    * @param string $tag
    * @param SetACLResponse $v
    */
    public function deserialize_SetACLResponse(iarchive &$in, string $tag, SetACLResponse &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $this->deserialize_Stat($in, 'stat', $v->stat);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 SetACLResponse
    *
    * @param SetACLResponse $v
    */
    public function deallocate_SetACLResponse(SetACLResponse &$v)
    {
        $this->deallocate_Stat($v->stat);
    }

    /******************************************
    * 序列化 WatcherEvent
    *
    * @param oarchive $out
    * @param string $tag
    * @param WatcherEvent $v
    */
    public function serialize_WatcherEvent(oarchive &$out, string $tag, WatcherEvent &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'type', $v->type);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'state', $v->state);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 WatcherEvent
    *
    * @param iarchive $in
    * @param string $tag
    * @param WatcherEvent $v
    */
    public function deserialize_WatcherEvent(iarchive &$in, string $tag, WatcherEvent &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'type', $v->type);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'state', $v->state);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 WatcherEvent
    *
    * @param WatcherEvent $v
    */
    public function deallocate_WatcherEvent(WatcherEvent &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 ErrorResponse
    *
    * @param oarchive $out
    * @param string $tag
    * @param ErrorResponse $v
    */
    public function serialize_ErrorResponse(oarchive &$out, string $tag, ErrorResponse &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'err', $v->err);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 ErrorResponse
    *
    * @param iarchive $in
    * @param string $tag
    * @param ErrorResponse $v
    */
    public function deserialize_ErrorResponse(iarchive &$in, string $tag, ErrorResponse &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->serialize_Int($in, 'err', $v->err);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 ErrorResponse
    *
    * @param ErrorResponse $v
    */
    public function deallocate_ErrorResponse(ErrorResponse &$v)
    {
    }

    /******************************************
    * 序列化 CreateResponse
    *
    * @param oarchive $out
    * @param string $tag
    * @param CreateResponse $v
    */
    public function serialize_CreateResponse(oarchive &$out, string $tag, CreateResponse &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 CreateResponse
    *
    * @param iarchive $in
    * @param string $tag
    * @param CreateResponse $v
    */
    public function deserialize_CreateResponse(iarchive &$in, string $tag, CreateResponse &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 CreateResponse
    *
    * @param CreateResponse $v
    */
    public function deallocate_CreateResponse(CreateResponse &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 ExistsRequest
    *
    * @param oarchive $out
    * @param string $tag
    * @param ExistsRequest $v
    */
    public function serialize_ExistsRequest(oarchive &$out, string $tag, ExistsRequest &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->serialize_Bool($out, 'watch', $v->watch);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 ExistsRequest
    *
    * @param iarchive $in
    * @param string $tag
    * @param ExistsRequest $v
    */
    public function deserialize_ExistsRequest(iarchive &$in, string $tag, ExistsRequest &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->deserialize_Bool($in, 'watch', $v->watch);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 ExistsRequest
    *
    * @param ExistsRequest $v
    */
    public function deallocate_ExistsRequest(ExistsRequest &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 ExistsResponse
    *
    * @param oarchive $out
    * @param string $tag
    * @param ExistsResponse $v
    */
    public function serialize_ExistsResponse(oarchive &$out, string $tag, ExistsResponse &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $this->serialize_Stat($out, 'stat', $v->stat);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 ExistsResponse
    *
    * @param iarchive $in
    * @param string $tag
    * @param ExistsResponse $v
    */
    public function deserialize_ExistsResponse(iarchive &$in, string $tag, ExistsResponse &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $this->deserialize_Stat($in, 'stat', $v->stat);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 ExistsResponse
    *
    * @param ExistsResponse $v
    */
    public function deallocate_ExistsResponse(ExistsResponse &$v)
    {
        $this->deallocate_Stat($v->stat);
    }

    /******************************************
    * 序列化 GetDataResponse
    *
    * @param oarchive $out
    * @param string $tag
    * @param GetDataResponse $v
    */
    public function serialize_GetDataResponse(oarchive &$out, string $tag, GetDataResponse &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'data', $v->data);
        $rc = $rc ? $rc : $this->serialize_Stat($out, 'stat', $v->stat);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 GetDataResponse
    *
    * @param iarchive $in
    * @param string $tag
    * @param GetDataResponse $v
    */
    public function deserialize_GetDataResponse(iarchive &$in, string $tag, GetDataResponse &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Buffer($in, 'data', $v->data);
        $rc = $rc ? $rc : $this->deserialize_Stat($in, 'stat', $v->stat);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 GetDataResponse
    *
    * @param GetDataResponse $v
    */
    public function deallocate_GetDataResponse(GetDataResponse &$v)
    {
        deallocate_Buffer($v->data);
        $this->deallocate_Stat($v->stat);
    }

    /******************************************
    * 序列化 GetChildrenResponse
    *
    * @param oarchive $out
    * @param string $tag
    * @param GetChildrenResponse $v
    */
    public function serialize_GetChildrenResponse(oarchive &$out, string $tag, GetChildrenResponse &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $this->serialize_String_vector($out, 'children', $v->children);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 GetChildrenResponse
    *
    * @param iarchive $in
    * @param string $tag
    * @param GetChildrenResponse $v
    */
    public function deserialize_GetChildrenResponse(iarchive &$in, string $tag, GetChildrenResponse &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $this->deserialize_String_vector($in, 'children', $v->children);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 GetChildrenResponse
    *
    * @param GetChildrenResponse $v
    */
    public function deallocate_GetChildrenResponse(GetChildrenResponse &$v)
    {
        $this->deallocate_String_vector($v->children);
    }

    /******************************************
    * 序列化 GetChildren2Response
    *
    * @param oarchive $out
    * @param string $tag
    * @param GetChildren2Response $v
    */
    public function serialize_GetChildren2Response(oarchive &$out, string $tag, GetChildren2Response &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $this->serialize_String_vector($out, 'children', $v->children);
        $rc = $rc ? $rc : $this->serialize_Stat($out, 'stat', $v->stat);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 GetChildren2Response
    *
    * @param iarchive $in
    * @param string $tag
    * @param GetChildren2Response $v
    */
    public function deserialize_GetChildren2Response(iarchive &$in, string $tag, GetChildren2Response &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $this->deserialize_String_vector($in, 'children', $v->children);
        $rc = $rc ? $rc : $this->deserialize_Stat($in, 'stat', $v->stat);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 GetChildren2Response
    *
    * @param GetChildren2Response $v
    */
    public function deallocate_GetChildren2Response(GetChildren2Response &$v)
    {
        $this->deallocate_String_vector($v->children);
        $this->deallocate_Stat($v->stat);
    }

    /******************************************
    * 序列化 GetACLResponse
    *
    * @param oarchive $out
    * @param string $tag
    * @param GetACLResponse $v
    */
    public function serialize_GetACLResponse(oarchive &$out, string $tag, GetACLResponse &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $this->serialize_ACL_vector($out, 'acl', $v->acl);
        $rc = $rc ? $rc : $this->serialize_Stat($out, 'stat', $v->stat);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 GetACLResponse
    *
    * @param iarchive $in
    * @param string $tag
    * @param GetACLResponse $v
    */
    public function deserialize_GetACLResponse(iarchive &$in, string $tag, GetACLResponse &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $this->deserialize_ACL_vector($om, 'acl', $v->acl);
        $rc = $rc ? $rc : $this->deserialize_Stat($om, 'stat', $v->stat);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 GetACLResponse
    *
    * @param GetACLResponse $v
    */
    public function deallocate_GetACLResponse(GetACLResponse &$v)
    {
        $this->deallocate_ACL_vector($v->children);
        $this->deallocate_Stat($v->stat);
    }

    /******************************************
    * 序列化 LearnerInfo
    *
    * @param oarchive $out
    * @param string $tag
    * @param LearnerInfo $v
    */
    public function serialize_LearnerInfo(oarchive &$out, string $tag, LearnerInfo &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'serverid', $v->serverid);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'protocolVersion', $v->protocolVersion);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 LearnerInfo
    *
    * @param iarchive $in
    * @param string $tag
    * @param LearnerInfo $v
    */
    public function deserialize_LearnerInfo(iarchive &$in, string $tag, LearnerInfo &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'serverid', $v->serverid);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'protocolVersion', $v->protocolVersion);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 LearnerInfo
    *
    * @param LearnerInfo $v
    */
    public function deallocate_LearnerInfo(LearnerInfo &$v)
    {
    }

    /******************************************
    * 序列化 Id_vector
    *
    * @param oarchive $out
    * @param string $tag
    * @param Id_vector $v
    */
    public function serialize_Id_vector(oarchive &$out, string $tag, Id_vector &$v)
    {
        $rc = $out->start_vector($out, $tag);
        $rc = $rc ? $rc : $this->serialize_Id($out, 'data', $v->data);
        $rc = $rc ? $rc : $out->end_vector($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 Id_vector
    *
    * @param iarchive $in
    * @param string $tag
    * @param Id_vector $v
    */
    public function deserialize_Id_vector(iarchive &$in, string $tag, Id_vector &$v)
    {
        $rc = $in->start_vector($in, $tag);
        $rc = $rc ? $rc : $this->deserialize_Id($in, 'data', $v->data);
        $rc = $rc ? $rc : $in->end_vector($in, $tag);

        return $rc;
    }

    /******************************************
    * 初始化 Id_vector
    *
    * @param Id_vector $v
    */
    public function allocate_Id_vector(Id_vector &$v)
    {
        if (!len) {
            $v->count = 0;
            $v->data = 0;
        } else {
            $v->count = len;
            $v->data = null;
        }

        return 0;
    }

    /******************************************
    * 释放 Id_vector
    *
    * @param Id_vector $v
    */
    public function deallocate_Id_vector(Id_vector &$v)
    {
        if ($v->data) {
            deallocate_Id($v->data);
//            $v->data = null;
        }

        return 0;
    }

    /******************************************
    * 序列化 QuorumPacket
    *
    * @param oarchive $out
    * @param string $tag
    * @param QuorumPacket $v
    */
    public function serialize_QuorumPacket(oarchive &$out, string $tag, QuorumPacket &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'type', $v->type);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'zxid', $v->zxid);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'data', $v->data);
        $rc = $rc ? $rc : $this->serialize_Id_vector($out, 'authinfo', $v->authinfo);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 QuorumPacket
    *
    * @param iarchive $in
    * @param string $tag
    * @param QuorumPacket $v
    */
    public function deserialize_QuorumPacket(iarchive &$in, string $tag, QuorumPacket &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'type', $v->type);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'zxid', $v->zxid);
        $rc = $rc ? $rc : $in->deserialize_Buffer($in, 'data', $v->data);
        $rc = $rc ? $rc : $this->deserialize_Id_vector($in, 'authinfo', $v->authinfo);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 QuorumPacket
    *
    * @param QuorumPacket $v
    */
    public function deallocate_QuorumPacket(QuorumPacket &$v)
    {
        deallocate_Buffer($v->data);
        $this->deallocate_Id_vector($v->authinfo);
    }

    /******************************************
    * 序列化 QuorumAuthPacket
    *
    * @param oarchive $out
    * @param string $tag
    * @param QuorumAuthPacket $v
    */
    public function serialize_QuorumAuthPacket(oarchive &$out, string $tag, QuorumAuthPacket &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'magic', $v->magic);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'status', $v->status);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'token', $v->token);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 QuorumAuthPacket
    *
    * @param iarchive $in
    * @param string $tag
    * @param QuorumAuthPacket $v
    */
    public function deserialize_QuorumAuthPacket(iarchive &$in, string $tag, QuorumAuthPacket &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'magic', $v->magic);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'status', $v->status);
        $rc = $rc ? $rc : $in->deserialize_Buffer($in, 'token', $v->token);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 QuorumAuthPacket
    *
    * @param QuorumAuthPacket $v
    */
    public function deallocate_QuorumAuthPacket(QuorumAuthPacket &$v)
    {
        deallocate_Buffer($v->token);
    }

    /******************************************
    * 序列化 FileHeader
    *
    * @param oarchive $out
    * @param string $tag
    * @param FileHeader $v
    */
    public function serialize_FileHeader(oarchive &$out, string $tag, FileHeader &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'magic', $v->magic);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'version', $v->version);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'dbid', $v->dbid);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 FileHeader
    *
    * @param iarchive $in
    * @param string $tag
    * @param FileHeader $v
    */
    public function deserialize_FileHeader(iarchive &$in, string $tag, FileHeader &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'magic', $v->magic);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'version', $v->version);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'dbid', $v->dbid);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 FileHeader
    *
    * @param FileHeader $v
    */
    public function deallocate_FileHeader(FileHeader &$v)
    {
    }

    /******************************************
    * 序列化 TxnHeader
    *
    * @param oarchive $out
    * @param string $tag
    * @param TxnHeader $v
    */
    public function serialize_TxnHeader(oarchive &$out, string $tag, TxnHeader &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'clientId', $v->clientId);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'cxid', $v->cxid);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'zxid', $v->zxid);
        $rc = $rc ? $rc : $out->serialize_Long($out, 'time', $v->time);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'type', $v->type);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 TxnHeader
    *
    * @param iarchive $in
    * @param string $tag
    * @param TxnHeader $v
    */
    public function deserialize_TxnHeader(iarchive &$in, string $tag, TxnHeader &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'clientId', $v->clientId);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'cxid', $v->cxid);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'zxid', $v->zxid);
        $rc = $rc ? $rc : $in->deserialize_Long($in, 'time', $v->time);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'type', $v->type);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 TxnHeader
    *
    * @param TxnHeader $v
    */
    public function deallocate_TxnHeader(TxnHeader &$v)
    {
    }

    /******************************************
    * 序列化 CreateTxnV0
    *
    * @param oarchive $out
    * @param string $tag
    * @param CreateTxnV0 $v
    */
    public function serialize_CreateTxnV0(oarchive &$out, string $tag, CreateTxnV0 &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'data', $v->data);
        $rc = $rc ? $rc : $this->serialize_ACL_vector($out, 'acl', $v->acl);
        $rc = $rc ? $rc : $out->serialize_Bool($out, 'ephemeral', $v->ephemeral);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 CreateTxnV0
    *
    * @param iarchive $in
    * @param string $tag
    * @param CreateTxnV0 $v
    */
    public function deserialize_CreateTxnV0(iarchive &$in, string $tag, CreateTxnV0 &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->deserialize_Buffer($in, 'data', $v->data);
        $rc = $rc ? $rc : $this->deserialize_ACL_vector($in, 'acl', $v->acl);
        $rc = $rc ? $rc : $in->deserialize_Bool($in, 'ephemeral', $v->ephemeral);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 CreateTxnV0
    *
    * @param CreateTxnV0 $v
    */
    public function deallocate_CreateTxnV0(CreateTxnV0 &$v)
    {
        deallocate_String($v->path);
        deallocate_Buffer($v->data);
        $this->deallocate_ACL_vector($v->acl);
    }

    /******************************************
    * 序列化 CreateTxn
    *
    * @param oarchive $out
    * @param string $tag
    * @param CreateTxn $v
    */
    public function serialize_CreateTxn(oarchive &$out, string $tag, CreateTxn &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'data', $v->data);
        $rc = $rc ? $rc : $this->serialize_ACL_vector($out, 'acl', $v->acl);
        $rc = $rc ? $rc : $out->serialize_Bool($out, 'ephemeral', $v->ephemeral);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'parentCVersion', $v->parentCVersion);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 CreateTxn
    *
    * @param iarchive $in
    * @param string $tag
    * @param CreateTxn $v
    */
    public function deserialize_CreateTxn(iarchive &$in, string $tag, CreateTxn &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->deserialize_Buffer($in, 'data', $v->data);
        $rc = $rc ? $rc : $this->deserialize_ACL_vector($in, 'acl', $v->acl);
        $rc = $rc ? $rc : $in->deserialize_Bool($in, 'ephemeral', $v->ephemeral);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'parentCVersion', $v->parentCVersion);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 CreateTxn
    *
    * @param CreateTxn $v
    */
    public function deallocate_CreateTxn(CreateTxn &$v)
    {
        deallocate_String($v->path);
        deallocate_Buffer($v->data);
        $this->deallocate_ACL_vector($v->acl);
    }

    /******************************************
    * 序列化 DeleteTxn
    *
    * @param oarchive $out
    * @param string $tag
    * @param DeleteTxn $v
    */
    public function serialize_DeleteTxn(oarchive &$out, string $tag, DeleteTxn &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 DeleteTxn
    *
    * @param iarchive $in
    * @param string $tag
    * @param DeleteTxn $v
    */
    public function deserialize_DeleteTxn(iarchive &$in, string $tag, DeleteTxn &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 DeleteTxn
    *
    * @param DeleteTxn $v
    */
    public function deallocate_DeleteTxn(DeleteTxn &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 SetDataTxn
    *
    * @param oarchive $out
    * @param string $tag
    * @param SetDataTxn $v
    */
    public function serialize_SetDataTxn(oarchive &$out, string $tag, SetDataTxn &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'data', $v->data);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'version', $v->version);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 SetDataTxn
    *
    * @param iarchive $in
    * @param string $tag
    * @param SetDataTxn $v
    */
    public function deserialize_SetDataTxn(iarchive &$in, string $tag, SetDataTxn &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->deserialize_Buffer($in, 'data', $v->data);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'version', $v->version);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 SetDataTxn
    *
    * @param SetDataTxn $v
    */
    public function deallocate_SetDataTxn(SetDataTxn &$v)
    {
        deallocate_String($v->path);
        deallocate_Buffer($v->data);
    }

    /******************************************
    * 序列化 CheckVersionTxn
    *
    * @param oarchive $out
    * @param string $tag
    * @param CheckVersionTxn $v
    */
    public function serialize_CheckVersionTxn(oarchive &$out, string $tag, CheckVersionTxn &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'version', $v->version);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 CheckVersionTxn
    *
    * @param iarchive $in
    * @param string $tag
    * @param CheckVersionTxn $v
    */
    public function deserialize_CheckVersionTxn(iarchive &$in, string $tag, CheckVersionTxn &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'version', $v->version);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 CheckVersionTxn
    *
    * @param CheckVersionTxn $v
    */
    public function deallocate_CheckVersionTxn(CheckVersionTxn &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 SetACLTxn
    *
    * @param oarchive $out
    * @param string $tag
    * @param SetACLTxn $v
    */
    public function serialize_SetACLTxn(oarchive &$out, string $tag, SetACLTxn &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $this->serialize_ACL_vector($out, 'acl', $v->acl);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'version', $v->version);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 SetACLTxn
    *
    * @param iarchive $in
    * @param string $tag
    * @param SetACLTxn $v
    */
    public function deserialize_SetACLTxn(iarchive &$in, string $tag, SetACLTxn &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $this->deserialize_ACL_vector($in, 'acl', $v->acl);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'version', $v->version);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 SetACLTxn
    *
    * @param SetACLTxn $v
    */
    public function deallocate_SetACLTxn(SetACLTxn &$v)
    {
        deallocate_String($v->path);
        $this->deallocate_ACL_vector($v->acl);
    }

    /******************************************
    * 序列化 SetMaxChildrenTxn
    *
    * @param oarchive $out
    * @param string $tag
    * @param SetMaxChildrenTxn $v
    */
    public function serialize_SetMaxChildrenTxn(oarchive &$out, string $tag, SetMaxChildrenTxn &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_String($out, 'path', $v->path);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'max', $v->max);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 SetMaxChildrenTxn
    *
    * @param iarchive $in
    * @param string $tag
    * @param SetMaxChildrenTxn $v
    */
    public function deserialize_SetMaxChildrenTxn(iarchive &$in, string $tag, SetMaxChildrenTxn &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_String($in, 'path', $v->path);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'max', $v->max);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 SetMaxChildrenTxn
    *
    * @param SetMaxChildrenTxn $v
    */
    public function deallocate_SetMaxChildrenTxn(SetMaxChildrenTxn &$v)
    {
        deallocate_String($v->path);
    }

    /******************************************
    * 序列化 CreateSessionTxn
    *
    * @param oarchive $out
    * @param string $tag
    * @param CreateSessionTxn $v
    */
    public function serialize_CreateSessionTxn(oarchive &$out, string $tag, CreateSessionTxn &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'timeOut', $v->timeOut);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 CreateSessionTxn
    *
    * @param iarchive $in
    * @param string $tag
    * @param CreateSessionTxn $v
    */
    public function deserialize_CreateSessionTxn(iarchive &$in, string $tag, CreateSessionTxn &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'timeOut', $v->timeOut);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 CreateSessionTxn
    *
    * @param CreateSessionTxn $v
    */
    public function deallocate_CreateSessionTxn(CreateSessionTxn &$v)
    {
    }

    /******************************************
    * 序列化 ErrorTxn
    *
    * @param oarchive $out
    * @param string $tag
    * @param ErrorTxn $v
    */
    public function serialize_ErrorTxn(oarchive &$out, string $tag, ErrorTxn &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'err', $v->err);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 ErrorTxn
    *
    * @param iarchive $in
    * @param string $tag
    * @param ErrorTxn $v
    */
    public function deserialize_ErrorTxn(iarchive &$in, string $tag, ErrorTxn &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $in->deserialize_Int($in, 'err', $v->err);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 ErrorTxn
    *
    * @param ErrorTxn $v
    */
    public function deallocate_ErrorTxn(ErrorTxn &$v)
    {
    }

    /******************************************
    * 序列化 Txn
    *
    * @param oarchive $out
    * @param string $tag
    * @param Txn $v
    */
    public function serialize_Txn(oarchive &$out, string $tag, Txn &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $out->serialize_Int($out, 'type', $v->type);
        $rc = $rc ? $rc : $out->serialize_Buffer($out, 'data', $v->data);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 Txn
    *
    * @param iarchive $in
    * @param string $tag
    * @param Txn $v
    */
    public function deserialize_Txn(iarchive &$in, string $tag, Txn &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $int->deserialize_Int($int, 'type', $v->type);
        $rc = $rc ? $rc : $int->deserialize_Buffer($int, 'data', $v->data);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 Txn
    *
    * @param Txn $v
    */
    public function deallocate_Txn(Txn &$v)
    {
        deallocate_Buffer($v->data);
    }

    /******************************************
    * 序列化 Txn_vector
    *
    * @param oarchive $out
    * @param string $tag
    * @param Txn_vector $v
    */
    public function serialize_Txn_vector(oarchive &$out, string $tag, Txn_vector &$v)
    {
        $rc = $out->start_vector($out, $tag);
        $rc = $rc ? $rc : $this->serialize_Txn($out, 'data', $v->data);
        $rc = $rc ? $rc : $out->end_vector($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 Txn_vector
    *
    * @param iarchive $in
    * @param string $tag
    * @param Txn_vector $v
    */
    public function deserialize_Txn_vector(iarchive &$in, string $tag, Txn_vector &$v)
    {
        $rc = $in->start_vector($in, $tag);
        $rc = $rc ? $rc : $this->deserialize_Txn($in, 'data', $v->data);
        $rc = $rc ? $rc : $in->end_vector($in, $tag);

        return $rc;
    }

    /******************************************
    * 初始化 Txn_vector
    *
    * @param Txn_vector $v
    * @param int $len
    */
    public function allocate_Txn_vector(Txn_vector &$v, int $len)
    {
        if (!$len) {
            $v->count = 0;
            $v->data = 0;
        } else {
            $v->count = $len;
            $v->data = null;
        }

        return 0;
    }

    /******************************************
    * 释放 Txn_vector
    *
    * @param Txn_vector $v
    */
    public function deallocate_Txn_vector(Txn_vector &$v)
    {
        if ($v->data) {
            $this->deallocate_Txn($v->data);
//            $v->data = null;
        }

        return 0;
    }

    /******************************************
    * 序列化 MultiTxn
    *
    * @param oarchive $out
    * @param string $tag
    * @param MultiTxn $v
    */
    public function serialize_MultiTxn(oarchive &$out, string $tag, MultiTxn &$v)
    {
        $rc = $out->start_record($out, $tag);
        $rc = $rc ? $rc : $this->serialize_Txn_vector($out, 'txns', $v->txns);
        $rc = $rc ? $rc : $out->end_record($out, $tag);

        return $rc;
    }

    /******************************************
    * 反序列化 MultiTxn
    *
    * @param iarchive $in
    * @param string $tag
    * @param MultiTxn $v
    */
    public function deserialize_MultiTxn(iarchive &$in, string $tag, MultiTxn &$v)
    {
        $rc = $in->start_record($in, $tag);
        $rc = $rc ? $rc : $this->deserialize_Txn_vector($in, 'txns', $v->txns);
        $rc = $rc ? $rc : $in->end_record($in, $tag);

        return $rc;
    }

    /******************************************
    * 释放 MultiTxn
    *
    * @param MultiTxn $v
    */
    public function deallocate_MultiTxn(MultiTxn &$v)
    {
        $this->deallocate_Txn_vector($v->txns);
    }
}
