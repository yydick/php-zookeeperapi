<?php

/* * **********************************************
 * 本程序于2019年02月13日
 * 由陈浩波编写完成
 * 任何人使用时请保留该声明
 */
define('BIG_ENDIAN', pack('L', 1) === pack('N', 1));
include_once 'spool/zookeeper/client/lib/Loader.php';
