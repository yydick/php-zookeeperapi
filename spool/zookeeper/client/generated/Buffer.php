<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Generated;

/**
 * Description of Buffer
 *
 * @author 陈浩波
 */
class Buffer {
    static public $buff;
    static public function len() {
	return strlen(self::$buff);
    }
}
