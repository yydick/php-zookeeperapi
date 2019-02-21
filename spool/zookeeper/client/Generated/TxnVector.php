<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Generated;


use Spool\Zookeeper\Client\Classes\Oarchive;
use Spool\Zookeeper\Client\Classes\Iarchive;
use Spool\Zookeeper\Client\Generated\Txn;
/**
 * Description of TxnVector
 *
 * @author 陈浩波
 */
class TxnVector {

    public $type;	      //int32_t
    public $data;	      //obj buffer

    public function __construct() {
	$this->type = 0;
	$this->data = new Txn();
    }
    public function serialize(Oarchive &$out, string $tag, TxnVector &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('type', $v->type);
	$rc = $rc ?: $this->data->serialize($out, 'data', $v->data);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, TxnVector &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('type', $v->type);
	$rc = $rc ?: $this->data->unserialize($in, 'data', $v->data);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
