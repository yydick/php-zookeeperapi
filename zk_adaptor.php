<?php
/************************************************
* 本程序于2018年03月25日
* 由陈浩波编写完成
* 任何人使用时请保留该声明
*/
namespace SPOOL\ZOOKEEPER\CLIENT;

include_once 'zookeeper.jute.php';
include_once 'zookeeper.php';
if(!defined('THREADED')){
    include_once 'st_adaptor.php';
}else{
    include_once 'mt_adaptor.php';
}

function PROCESS_SESSION_EVENT(zhandle_t $zh, $newstate){
    return queue_session_event($zh, $newstate);
}
