<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Zoo;

use Spool\Zookeeper\Client\Zoo\Zookeeper;
/**
 * Description of CompletionT
 *
 * @author 陈浩波
 */
class CompletionT {
     
    public $type;		    //int 确认回调函数的类型
    public $fn;			    //回调函数指针
    public $clist;		    //多重调用的返回值
}
