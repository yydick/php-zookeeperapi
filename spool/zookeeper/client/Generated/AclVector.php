<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Generated;


use Spool\Zookeeper\Client\Classes\Oarchive;
use Spool\Zookeeper\Client\Classes\Iarchive;
use Spool\Zookeeper\Client\Generated\Acl;

/**
 * Description of AclVector
 *
 * @author 陈浩波
 */
class AclVector {

    public $count;	      //int32
    public $data;	      //obj Acl

    public function __construct() {
	$this->count = 0;
	$this->data = new Acl();
    }
    public function serialize(Oarchive &$out, string $tag, AclVector &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('count', $v->count);
	$rc = $rc ?: $this->data->serialize($out, 'data', $v->data);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, AclVector &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('count', $v->count);
	$rc = $rc ?: $this->data->unserialize($in, 'data', $v->data);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
