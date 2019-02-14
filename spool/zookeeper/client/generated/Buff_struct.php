<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Generated;

/**
 * Description of Buff_struct
 *
 * @author 陈浩波
 */
class Buff_struct {

    public $len; //int
    public $off; //int
    public $buffer; //string

    public function __construct(int $len = 0, int $off = 0, string $buffer = '') {
	$this->len = $len;
	$this->off = $off;
	$this->buffer = $buffer;
    }

    static public function resizeBuffer(Buff_struct &$s, int $newlen) {
	while ($s->len < $newlen) {
	    $s->len *= 2;
	}
	return 0;
    }

}
