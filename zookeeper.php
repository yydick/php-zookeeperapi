<?php

/* * **********************************************
 * 本程序于2019年02月13日
 * 由陈浩波编写完成
 * 任何人使用时请保留该声明
 */
define('BIG_ENDIAN', pack('L', 1) === pack('N', 1));
defined("LIB_PATH") || define("LIB_PATH", '');
include_once 'spool/Zookeeper/Client/Lib/Proto.php';
include_once 'spool/Zookeeper/Client/Lib/Loader.php';

use Spool\Zookeeper\Client\Lib\Loader;
use Spool\Zookeeper\Client\Generated\Acl;
use Spool\Zookeeper\Client\Zoo\CompletionT;

Loader::register();

$acl = new Acl();
$c = new CompletionT();
echo "Hello world!\n";
echo $c->type, "\n";
