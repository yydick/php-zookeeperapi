<?php
/************************************************
* 本程序于2018年03月25日
* 由陈浩波编写完成
* 任何人使用时请保留该声明
*/
namespace SPOOL\ZOOKEEPER\CLIENT;

include_once 'zookeeper.jute.php';
include_once 'hashtable_itr.php';
include_once 'zookeeper.php';
abstract class _zk_hashtable extends \SplStack
{
    public $ht;
    public function __construct(hashtable $ht = null){
        $this->ht = $ht;
    }
}

class zk_hashtable extends _zk_hashtable{
};
class watcher_object_list_t extends watcher_object_list{
};

class entry extends \SplStack
{
    public $k, $v, $h, $next;
    public function __construct($k = null, $v = null, int $h = 0, entry $next = null){
        $this->k = $k;
        $this->v = $v;
        $this->h = $h;
        $this->next = $next;
    }
};

class hashtable {
    public $tablelength, $table, $entrycount, $loadlimit, $primeindex, $hashfn, $eqfn;
    public function __construct(int $tablelength = 0, entry $table = null, int $entrycount = 0, int $loadlimit = 0, int $primeindex = 0, callback $hashfn = null, callback $eqfn = null){
        $this->tablelength = $tablelength;
        $this->table = $table;
        $this->entrycount = $entrycount;
        $this->loadlimit = $loadlimit;
        $this->primeindex = $primeindex;
        $this->hashfn = $hashfn;
        $this->eqfn = $eqfn;
    }
};

function indexFor(int $tablelength, int $hashvalue) : int
{
    return ($hashvalue % $tablelength);
};

$primes = [
53, 97, 193, 389,
769, 1543, 3079, 6151,
12289, 24593, 49157, 98317,
196613, 393241, 786433, 1572869,
3145739, 6291469, 12582917, 25165843,
50331653, 100663319, 201326611, 402653189,
805306457, 1610612741
];
//const unsigned int prime_table_length = sizeof(primes)/sizeof(primes[0]);
$prime_table_length = count($primes);
$max_load_factor = 0.65;

function collectWatchers(zhandle_t $zh, int $type, strint $path) : watcher_object_list_t
{
//    struct watcher_object_list *list = create_watcher_object_list(0); 
    $head = new watcher_object_t();
    $list = create_watcher_object_list($head);

    if($type==ZOO_SESSION_EVENT){
//        watcher_object_t defWatcher;
        $defWatcher = new watcher_object_t();
        $defWatcher->watcher = $zh->watcher;
        $defWatcher->context = $zh->context;
        add_to_list($list, $defWatcher, 1);
        collect_session_watchers($zh, $list);
        return $list;
    }
    switch($type){
    case CREATED_EVENT_DEF:
    case CHANGED_EVENT_DEF:
        // look up the watchers for the path and move them to a delivery list
        add_for_event($zh->active_node_watchers, $path, $list);
        add_for_event($zh->active_exist_watchers, $path, $list);
        break;
    case CHILD_EVENT_DEF:
        // look up the watchers for the path and move them to a delivery list
        add_for_event($zh->active_child_watchers, $path, $list);
        break;
    case DELETED_EVENT_DEF:
        // look up the watchers for the path and move them to a delivery list
        add_for_event($zh->active_node_watchers, $path, $list);
        add_for_event($zh->active_exist_watchers, $path, $list);
        add_for_event($zh->active_child_watchers, $path, $list);
        break;
    }
    return $list;
}

/* the following functions are for testing only */
class hashtable_impl extends hashtable{
}

function getImpl(zk_hashtable &$ht) : hashtable_impl
{
    return $ht->ht;
}

function getFirstWatcher(zk_hashtable &$ht, string &$path) : watcher_object_t
{
    $wl = new watcher_object_list_t();
    $wl = hashtable_search($ht->ht, $path);
    if($wl)
        return $wl->head;
    return 0;
}
/* end of testing functions */
/*
function clone_watcher_object(watcher_object_t &$wo) : watcher_object_t
{
    $res = new watcher_object_t();
    $res->watcher = $wo->watcher;
    $res->context = $wo->context;
    return $res;
}
//*/
function string_hash_djb2(string &$str) : int
{
    $hash = 5381;
    $c = 0;
    $cstr = $str;
    while($c = substr($str, $i++, 1)){
        $hash = ($hash << 5) + $hash + ord($c); /* hash * 33 + c */
    }

    return $hash;
}

function string_equal(&$key1, &$key2) : int
{
    return strcmp($key1, $key2) == 0;
}
/*
function create_watcher_object(watcher_fn $watcher, &$ctx) : watcher_object_t
{
    $wo = new watcher_object_t();
    $wo->watcher = $watcher;
    $wo->context = $ctx;
    return $wo;
}
//*/
function create_watcher_object_list(watcher_object_t &$head) : watcher_object_list_t
{
    $wl = new watcher_object_list_t();
    $wl->head = $head;
    return $wl;
}

function destroy_watcher_object_list(watcher_object_list_t &$list)
{
    $e = new watcher_object_t();
    $e = NULL;

    if(!$list)
        return;
    $e = &$list->head;
    while($e->valid()){
        $e->pop();
        $e->next();
    }
    $list = null;
}

function create_zk_hashtable() : zk_hashtable
{
    $ht = new zk_hashtable();
    $ht->ht = create_hashtable(32, "SPOOL\\ZOOKEEPER\\CLIENT\\string_hash_djb2", "SPOOL\\ZOOKEEPER\\CLIENT\\string_equal");
    return $ht;
}

function do_clean_hashtable(zk_hashtable &$ht)
{
    $it = new hashtable();
    $hasMore = 0;
    if(!$ht->ht || hashtable_count($ht->ht)==0)
        return;
    $it = hashtable_iterator($ht->ht);
    do {
        $w = new watcher_object_list_t();
        $w = hashtable_iterator_value($it);
        destroy_watcher_object_list($w);
        $hasMore = hashtable_iterator_remove($it);
    } while($hasMore);
    unset($it);
}

function destroy_zk_hashtable(zk_hashtable &$ht)
{
    if($ht && $ht->ht){
        do_clean_hashtable($ht);
        hashtable_destroy($ht->ht,0);
        unset($ht);
    }
}

function add_to_list(watcher_object_list_t &$wl, watcher_object_t &$wo, int $clone) : int
{
    if (search_watcher($wl, $wo)==0) {
        $cloned = &$wo;
        if ($clone) {
            $cloned = clone_watcher_object($wo);
//            assert(cloned);
        }
//        cloned->next = (*wl)->head;
//        (*wl)->head = cloned;
        $wl->head[] = $cloned;
        return 1;
    } else if (!$clone) {
        // If it's here and we aren't supposed to clone, we must destroy
//        free(wo);
        $wo = null;
    }
    return 0;
}

function search_watcher(watcher_object_list_t &$wl,watcher_object_t &$wo) : watcher_object_t
{
//    watcher_object_t* wobj=(*wl)->head;
    $wobj = &$wl->head;
//    while(wobj!=0){
    foreach($wobj as $obj){
        if($obj->watcher == $wo->watcher && $obj->context == $wo->context)
            return $obj;
    }
    return 0;
}

function clone_watcher_object(watcher_object_t &$wo) : watcher_object_t
{
//    watcher_object_t* res=calloc(1,sizeof(watcher_object_t));
//    assert(res);
//    res->watcher=wo->watcher;
//    res->context=wo->context;
    $res = new watcher_object_t();
    $res->watcher = $wo->watcher;
    $res->context = $wo->context;
    return $res;
}

function collect_session_watchers(zhandle_t &$zh, watcher_object_list_t &$list)
{
    copy_table($zh->active_node_watchers, $list);
    copy_table($zh->active_exist_watchers, $list);
    copy_table($zh->active_child_watchers, $list);
}

function copy_table(zk_hashtable &$from, watcher_object_list_t &$to) {
    /*
    struct hashtable_itr *it;
    int hasMore;
    if(hashtable_count(from->ht)==0)
        return;
    it=hashtable_iterator(from->ht);
    do {
        watcher_object_list_t *w = hashtable_iterator_value(it);
        copy_watchers(w, to, 1);
        hasMore=hashtable_iterator_advance(it);
    } while(hasMore);
    free(it);
    */
    if(count($from->ht) == 0)
        return;
    foreach($from->ht as $w){
        copy_watchers($w, $to, 1);
    }
}

function copy_watchers(watcher_object_list_t &$from, watcher_object_list_t &$to, int $clone)
{
    /*
    watcher_object_t* wo=from->head;
    while(wo){
        watcher_object_t *next = wo->next;
        add_to_list(&to, wo, clone);
        wo=next;
    }
    */
    $wo = $from->head;
    foreach($wo as $next){
        add_to_list($to, $next, $clone);
    }
}

function add_for_event(zk_hashtable &$ht, string $path, watcher_object_list_t &$list)
{
//    watcher_object_list_t* wl;
//    wl = (watcher_object_list_t*)hashtable_remove(ht->ht, path);
    $wl = $ht->ht[$path];
    if ($wl) {
        copy_watchers($wl, $list, 0);
        // Since we move, not clone the watch_objects, we just need to free the
        // head pointer
        
//        free(wl);
    }
}

function hashtable_destroy(hashtable &$h, int $free_values)
{
    /*
    unsigned int i;
    struct entry *e, *f;
    struct entry **table = h->table;
    
    if (free_values)
    {
        for (i = 0; i < h->tablelength; i++)
        {
            e = table[i];
            while (NULL != e)
            { f = e; e = e->next; freekey(f->k); free(f->v); free(f); }
        }
    }
    else
    {
        for (i = 0; i < h->tablelength; i++)
        {
            e = table[i];
            while (NULL != e)
            { f = e; e = e->next; freekey(f->k); free(f); }
        }
    }
    free(h->table);
    free(h);
    //*/
    $h = null;
}

function do_insert_watcher_object(zk_hashtable &$ht, strint &$path, watcher_object_t &$wo) : int
{
    $res = 1;
//    watcher_object_list_t* wl;

    $wl = hashtable_search($ht->ht,$path);
    if($wl==0){
//        int res;
        /* inserting a new path element */
        $res = hashtable_insert($ht->ht,$path,create_watcher_object_list($wo));
//        assert(res);
    }else{
        /*
         * Path already exists; check if the watcher already exists.
         * Don't clone the watcher since it's allocated on the heap --- avoids
         * a memory leak and saves a clone operation (calloc + copy).
         */
        $res = add_to_list($wl, $wo, 0);
    }
    return $res;
}

//function hashtable_insert(struct hashtable *h, void *k, void *v) : int
function hashtable_insert(hashtable &$h, string $k, $v) : int
{
    /* This method allows duplicate keys - but they shouldn't be used */
    /*
    unsigned int index;
    struct entry *e;
    if (++(h->entrycount) > h->loadlimit)
    {
        /* Ignore the return value. If expand fails, we should
         * still try cramming just this value into the existing table
         * -- we may not have memory for a larger table, but one more
         * element may be ok. Next time we insert, we'll try expanding again.*/
         /*
        hashtable_expand(h);
    }
    e = (struct entry *)malloc(sizeof(struct entry));
    if (NULL == e) { --(h->entrycount); return 0; }
    //oom
    e->h = hash(h,k);
    index = indexFor(h->tablelength,e->h);
    e->k = k;
    e->v = v;
    e->next = h->table[index];
    h->table[index] = e;
    //*/
    $h[$k] = $v;
    if(++$h->entrycount > $h->loadlimit){
        hashtable_expand($h);
    }
    $e = new entry();
    $e->h = hash($h, $k);
    $index = indexFor($h->tablelength, $e->h);
    $e->k = $k;
    $e->v = $v;
    $e->next = $h->table[$index];
    $h->table[$index] = $e;
    return -1;
}

//function hashtable_search(struct hashtable *h, void *k)
function hashtable_search(hashtable &$h, string $k)
{
/*
    struct entry *e;
    unsigned int hashvalue, index;
    hashvalue = hash(h,k);
    index = indexFor(h->tablelength,hashvalue);
    e = h->table[index];
    while (NULL != e)
    {
        /* Check hash value to short circuit heavier comparison */
/*        if ((hashvalue == e->h) && (h->eqfn(k, e->k))) return e->v;
        e = e->next;
    }
//*/
    $e = new entry();
    $hashvalue = hash($h, $k);
    $index = indexFor($h->tablelength, $hashvalue);
    $e = $h->table[$index];
    while($e->valid()){
        if($hashvalue == $e->h && $h->eqfn($k, $e->k)) return $e->v;
        $e->next();
    }
    return NULL;
}

function hashtable_remove(hashtable $h, string $k)
{
    /* TODO: consider compacting the table when the load factor drops enough,
     *       or provide a 'compact' method. */
    /*
    struct entry *e;
    struct entry **pE;
    void *v;
    unsigned int hashvalue, index;

    hashvalue = hash(h,k);
    index = indexFor(h->tablelength,hash(h,k));
    pE = &(h->table[index]);
    e = *pE;
    while (NULL != e)
    {*/
        /* Check hash value to short circuit heavier comparison */
    /*
        if ((hashvalue == e->h) && (h->eqfn(k, e->k)))
        {
            *pE = e->next;
            h->entrycount--;
            v = e->v;
            freekey(e->k);
            free(e);
            return v;
        }
        pE = &(e->next);
        e = e->next;
    }
    */
    $e = new entry();
    $hashvalue = hash($h, $k);
    $index = indexFor($h->tablelength, hash($h, $k));
    $e = $h->table[$index];
    while (NULL != $e){
        if (($hashvalue == $e->h) && ($h->eqfn($k, $e->k)))
        {
            $h->entrycount--;
            $v = $e->v;
            $h->table->offsetUnset($e->k);
            return $v;
        }
        $e = $e->next();
    }
    return NULL;
}

function hashtable_count(hashtable &$h) : int
{
    return count($h);
}

function hashtable_expand(hashtable &$h) : int
{
    return -1;
}

//int hash(struct hashtable *h, void *k)
function hash(hashtable &$h, $k) : int
{
    /* Aim to protect against poor hash functions by adding logic here
     * - logic taken from java 1.4 hashtable source */
    /*
    unsigned int i = h->hashfn(k);
    i += ~(i << 9);
    i ^=  ((i >> 14) | (i << 18)); /* >>> */
//    i +=  (i << 4);
//    i ^=  ((i >> 10) | (i << 22)); /* >>> */
//    return i;
    //*/
    $i = $h->hashfn($k);
    $i += ~($i << 9);
    $i ^=  (($i >> 14) | ($i << 18)); /* >>> */
    $i +=  ($i << 4);
    $i ^=  (($i >> 10) | ($i << 22)); /* >>> */
    return $i;
}

//hashtable create_hashtable(unsigned int minsize, unsigned int (*hashf) (void*), int (*eqf) (void*,void*))
function create_hashtable(int $minsize, Callable $hashf, Callable $eqf) : hashtable
{
//    unsigned int pindex, size = primes[0];
    /* Check requested hashtable isn't too large */
//    if (minsize > (1u << 30)) return NULL;
    /* Enforce size as prime */
    /*
    for (pindex=0; pindex < prime_table_length; pindex++) {
        if (primes[pindex] > minsize) { size = primes[pindex]; break; }
    }
    h = (struct hashtable *)malloc(sizeof(struct hashtable));
    if (NULL == h) return NULL; 
    h->table = (struct entry **)malloc(sizeof(struct entry*) * size);
    if (NULL == h->table) { free(h); return NULL; } 
    memset(h->table, 0, size * sizeof(struct entry *));
    h->tablelength  = size;
    h->primeindex   = pindex;
    h->entrycount   = 0;
    h->hashfn       = hashf;
    h->eqfn         = eqf;
    h->loadlimit    = (unsigned int) ceil(size * max_load_factor);
    */
    global $prime_table_length, $max_load_factor, $primes;
    $pindex = $size = $primes[0];
    /* Check requested hashtable isn't too large */
    if ($minsize > (1 << 30)) return NULL;
    /* Enforce size as prime */
    for ($pindex = 0; $pindex < $prime_table_length; $pindex++) {
        if ($primes[$pindex] > $minsize) { $size = $primes[$pindex]; break; }
    }
    $h = new hashtable();
    $h->table = new entry();
    $h->tablelength  = $size;
    $h->primeindex   = $pindex;
    $h->entrycount   = 0;
    $h->hashfn       = $hashf;
    $h->eqfn         = $eqf;
    $h->loadlimit    = ceil($size * $max_load_factor);
    return $h;
}

function deliverWatchers(zhandle_t &$zh, int $type,int $state, string $path, watcher_object_list_t &$list)
{
    if (!$list) return;
    do_foreach_watcher($list->head, $zh, $path, $type, $state);
    destroy_watcher_object_list( $list );
    $list = 0;
}

function do_foreach_watcher(watcher_object_t &$wo, zhandle_t &$zh, string $path,int $type,int $state)
{
    // session event's don't have paths
    $client_path = $type != ZOO_SESSION_EVENT ? sub_string($zh, $path) : $path;
    /*
    while($wo != 0){
        $wo->watcher(zh,type,state,client_path,wo->context);
        wo=wo->next;
    }
    //*/
    foreach($wo as $func){
        if(is_callable($func)){
            call_user_func($func, $zh, $type, $state, $client_path, $wo->context);
        }
    }
    free_duplicate_path($client_path, $path);
}

function collect_keys(zk_hashtable &$ht, int &$count) : string
{
    /*
    char **list;
    struct hashtable_itr *it;
    int i;

    *count = hashtable_count(ht->ht);
    list = calloc(*count, sizeof(char*));
    it=hashtable_iterator(ht->ht);
    for(i = 0; i < *count; i++) {
        list[i] = strdup(hashtable_iterator_key(it));
        hashtable_iterator_advance(it);
    }
    free(it);
    return list;
    //*/
    $count = count($ht->ht);
    $list = $ht->ht;
    return $list;
}

function insert_watcher_object(zk_hashtable &$ht, string &$path, watcher_object_t &$wo) : int
{
    $res = 0;
    $res = do_insert_watcher_object($ht, $path, $wo);
    return $res;
}

function create_watcher_object(watcher_fn $watcher, $ctx) : watcher_object_t
{
    $wo = new watcher_object_t();
    assert($wo);
    $wo->watcher = $watcher;
    $wo->context = $ctx;
    return $wo;
}

function activateWatcher(zhandle_t &$zh, watcher_registration_t &$reg, int $rc)
{
    if($reg){
        /* in multithreaded lib, this code is executed 
         * by the IO thread */
        /*
        zk_hashtable *ht = reg->checker(zh, rc);
        if(ht){
            insert_watcher_object(ht,reg->path,
                    create_watcher_object(reg->watcher, reg->context));
        }
        //*/
        $ht = new zk_hashtable();
        $ht = $reg->checker($zh, $rc);
        if($ht){
            insert_watcher_object($ht, $reg->path,
                    create_watcher_object($reg->watcher, $reg->context));
        }
    }    
}
