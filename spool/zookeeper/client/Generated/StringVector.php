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
 * Description of String_vector
 *
 * @author 陈浩波
 */
class StringVector {
    public $count;	//int32_t
    public $data;	//string
    public function serialize(Oarchive &$out, string $tag, StringVector &$v) {
	$count = $v->count;
	$rc = $out->startRecord($tag);
	for ($i = 0; $i < $count; $i++){
	    $rc = $rc ?: $out->serializeString("data", $v->data[$i]);
	}
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }
    public function deserialize(Iarchive &$in, string $tag, StringVector &$v) {
	$count = $v->count;
	$rc = $in->startRecord($tag);
	for ($i = 0; $i < $count; $i++){
	    $rc = $rc ?: $in->deserializeString("value", $v->data[$i]);
	}
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
