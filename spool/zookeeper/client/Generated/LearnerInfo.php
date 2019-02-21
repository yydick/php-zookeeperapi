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
 * Description of LearnerInfo
 *
 * @author 陈浩波
 */
class LearnerInfo {

    public $serverid;	      //int64_t
    public $protocolVersion; //int32_t

    public function __construct() {
	$this->serverid = 0;
	$this->protocolVersion = 0;
    }
    public function serialize(Oarchive &$out, string $tag, LearnerInfo &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeLong('serverid', $v->serverid);
	$rc = $rc ?: $out->serializeInt('protocolVersion', $v->protocolVersion);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, LearnerInfo &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeLong('serverid', $v->serverid);
	$rc = $rc ?: $in->deserializeInt('protocolVersion', $v->protocolVersion);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
