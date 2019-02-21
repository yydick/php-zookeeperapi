<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Generated;


use Spool\Zookeeper\Client\Classes\Oarchive;
use Spool\Zookeeper\Client\Classes\Iarchive;
use Spool\Zookeeper\Client\Generated\Id;
/**
 * Description of IdVector
 *
 * @author 陈浩波
 */
class IdVector {

    public $count;	      //int32_t
    public $data;	      //obj Id

    public function __construct() {
	$this->count = 0;
	$this->data = new Id();
    }
    public function serialize(Oarchive &$out, string $tag, IdVector &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('count', $v->count);
	$rc = $rc ?: $this->data->serialize($out, 'data', $v->data);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, IdVector &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('count', $v->count);
	$rc = $rc ?: $this->data->unserialize($in, 'data', $v->data);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
