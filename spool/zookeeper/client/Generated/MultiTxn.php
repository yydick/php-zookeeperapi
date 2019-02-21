<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Generated;


use Spool\Zookeeper\Client\Classes\Oarchive;
use Spool\Zookeeper\Client\Classes\Iarchive;
use Spool\Zookeeper\Client\Generated\TxnVector;
/**
 * Description of TxnVector
 *
 * @author 陈浩波
 */
class MultiTxn {

    public $txns;	      //obj TxnVector

    public function __construct() {
	$this->txns = new TxnVector();
    }
    public function serialize(Oarchive &$out, string $tag, MultiTxn &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $this->data->serialize($out, 'txns', $v->txns);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, MultiTxn &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $this->data->unserialize($in, 'txns', $v->txns);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
