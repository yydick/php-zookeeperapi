<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Classes;

use Spool\Zookeeper\Client\Classes\Sizeof;
use Spool\Zookeeper\Client\Classes\Oarchive;
use Spool\Zookeeper\Client\Classes\Iarchive;

/**
 * Description of recordio
 *
 * @author 陈浩波
 */
class Recordio {

    public function createBufferOarchive() {
	$oa = new Oarchive();
	$oa->priv = new Buff_struct(128, 0, '');
	return $oa;
    }
    public function closeBufferOarchive(Oarchive &$oa) {
	$oa = NULL;
    }
    public function getBuffer(Oarchive $oa) {
	return $oa->priv->buffer;
    }
    public function getBufferLen(Oarchive $oa) {
	return $oa->priv->len;
    }
    
    public function createBufferIarchive(string $buffer, int $len) {
	$ia = new Iarchive();
	$ia->priv = new Buff_struct($len, 0, $buffer);
	return $ia;
    }
    public function closeBufferIarchive(Iarchive &$ia) {
	$ia = NULL;
    }
    
    public function zoo_htonll(string $v) : int {
	if (BIG_ENDIAN) {
	    return $v;
	} else {
	    $s = '';
	    for ($i = 0; $i < Sizeof::INT64_T ; $i++) {
		$s = $v{$i} . $s;
	    }
	}
	return $s;
    }

}
