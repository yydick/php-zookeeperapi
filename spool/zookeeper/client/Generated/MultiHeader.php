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
 * Description of MultiHeader
 *
 * @author 陈浩波
 */
class MultiHeader {

    public $type;	    //int32_t
    public $done;	    //int32_t
    public $err;	    //int32_t

    public function __construct() {
	$this->type = 0;
	$this->done = 0;
	$this->err = 0;
    }
    public function serialize(Oarchive &$out, string $tag, MultiHeader &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('type', $v->type);
	$rc = $rc ?: $out->serializeBool('done', $v->done);
	$rc = $rc ?: $out->serializeInt('err', $v->err);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, MultiHeader &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('type', $v->type);
	$rc = $rc ?: $in->deserializeBool('done', $v->done);
	$rc = $rc ?: $in->deserializeInt('err', $v->err);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
