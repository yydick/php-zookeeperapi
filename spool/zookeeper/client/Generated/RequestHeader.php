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
 * Description of Acl
 *
 * @author 陈浩波
 */
class RequestHeader {

    public $xid;	      //int32_t
    public $type;	      //int32_t

    public function __construct() {
	$this->xid = 0;
	$this->type = 0;
    }
    public function serialize(Oarchive &$out, string $tag, RequestHeader &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('xid', $v->xid);
	$rc = $rc ?: $out->serializeInt('type', $v->type);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, Acl &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('xid', $v->xid);
	$rc = $rc ?: $in->deserializeInt('type', $v->type);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
