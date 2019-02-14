<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Generated;

use Spool\Zookeeper\Client\Lib\ZookeeperException;
/**
 *
 * @author 陈浩波
 */
class Sizeof {
    const BOOLEAN = 1;
    const INT32_T = 4;
    const INT64_T = 8;
    const STRING = 2;

    static public function sizeof($param, string $type) {
	$sizeof = 0;
	switch ($type) {
	    case self::BOOLEAN:
		$sizeof = self::BOOLEAN;
		break;
	    case self::INT32_T:
		$sizeof = self::INT32_T;
		break;
	    case self::INT64_T:
		$sizeof = self::INT64_T;
		break;
	    case self::STRING:
		$sizeof = strlen($param);
		break;
	    default:
		throw new ZookeeperException('Not set the type!', 1);
		break;
	}
	return $sizeof;
    }

}
