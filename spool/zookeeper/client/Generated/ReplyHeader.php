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
 * Description of ReplyHeader
 *
 * @author 陈浩波
 */
class ReplyHeader {

    public $xid;	      //int32_t
    public $zxid;	      //int64_t
    public $err;	      //int32_t

    public function __construct() {
	$this->xid = 0;
	$this->zxid = 0;
	$this->err = 0;
    }
    public function serialize(Oarchive &$out, string $tag, ReplyHeader &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('xid', $v->xid);
	$rc = $rc ?: $out->serializeLong('zxid', $v->zxid);
	$rc = $rc ?: $out->serializeInt('err', $v->err);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, ReplyHeader &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('xid', $v->xid);
	$rc = $rc ?: $in->deserializeLong('zxid', $v->zxid);
	$rc = $rc ?: $in->deserializeInt('err', $v->err);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
