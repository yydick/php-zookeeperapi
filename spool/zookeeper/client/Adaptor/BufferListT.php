<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Adaptor;

/**
 * Description of BufferList
 *
 * @author 陈浩波
 */
class BufferListT {
    public $buffer;	    //string	字符串的值
    public $len;	    //int	字符串长度
    public $curr_offset;    //int	头部的偏移量，后面是内容
    public $next;	    //obj BufferList	下一个类的指针
    public function __construct() {
	$this->buffer = '';
	$this->len = 0;
	$this->curr_offset = 0;
	$this->next = new BufferListT();
    }
}

