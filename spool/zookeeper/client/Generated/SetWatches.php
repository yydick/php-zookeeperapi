<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Generated;

use Spool\Zookeeper\Client\Classes\Oarchive;
use Spool\Zookeeper\Client\Classes\Iarchive;
use Spool\Zookeeper\Client\Generated\StringVector;
/**
 * Description of SetWatches
 *
 * @author 陈浩波
 */
class SetWatches {
    public $relativeZxid;	//int64_t
    public $dataWatches;	//StringVector
    public $existWatches;	//StringVector
    public $childWatches;	//StringVector
    public function __construct() {
	$this->relativeZxid = 0;
	$this->dataWatches = new StringVector();
	$this->existWatches = new StringVector();
	$this->childWatches = new StringVector();
    }
    public function serialize(Oarchive &$out, string $tag, SetWatches &$v) {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeLong('relativeZxid', $v->relativeZxid);
	$rc = $rc ?: $this->dataWatches->serialize($out, 'dataWatches', $v->dataWatches);
	$rc = $rc ?: $this->existWatches->serialize($out, 'existWatches', $v->existWatches);
	$rc = $rc ?: $this->childWatches->serialize($out, 'childWatches', $v->childWatches);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }
    public function deserialize(Iarchive &$in, string $tag, SetWatches &$v) {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->serializeLong('relativeZxid', $v->relativeZxid);
	$rc = $rc ?: $this->dataWatches->deserialize($in, 'dataWatches', $v->dataWatches);
	$rc = $rc ?: $this->existWatches->deserialize($in, 'existWatches', $v->existWatches);
	$rc = $rc ?: $this->childWatches->deserialize($in, 'childWatches', $v->childWatches);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
