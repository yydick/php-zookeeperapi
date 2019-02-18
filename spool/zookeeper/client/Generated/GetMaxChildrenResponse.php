<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Generated;


use Spool\Zookeeper\Client\Classes\Oarchive;
use Spool\Zookeeper\Client\Classes\Iarchive;

/**
 * Description of GetMaxChildrenRequest
 *
 * @author 陈浩波
 */
class GetMaxChildrenRequest {

    public $max;	      //int32_t

    public function __construct() {
	$this->max = 0;
    }
    public function serialize(Oarchive &$out, string $tag, GetMaxChildrenRequest &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('max', $v->max);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, GetMaxChildrenRequest &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('max', $v->max);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
