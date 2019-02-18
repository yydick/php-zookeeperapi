<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Classes;

/**
 * Description of Buffer
 *
 * @author 陈浩波
 */
class Buffer {
    public $len;
    public $buff;
    public function __construct(int $len = 0, string $buff = '') {
	$this->len = $len;
	$this->buff = $buff;
    }
}
