<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Generated;

use Spool\Zookeeper\Client\Generated\Buff_struct;
use Spool\Zookeeper\Client\Generated\Sizeof;

/**
 * Description of recordio
 *
 * @author 陈浩波
 */
class Recordio {

    public function zoo_htonll(string $v): int {
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
