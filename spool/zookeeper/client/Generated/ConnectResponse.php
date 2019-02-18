<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Generated;

use Spool\Zookeeper\Client\Classes\Oarchive;
use Spool\Zookeeper\Client\Classes\Iarchive;
use Spool\Zookeeper\Client\Classes\Buffer;

/**
 * Description of ConnectRequest
 *
 * @author 陈浩波
 */
class ConnectResponse {
    public $protocolVersion;	    //int32_t
    public $timeOut;		    //int32_t
    public $sessionId;		    //int64_t
    public function __construct() {
	$this->protocolVersion = 0;
	$this->timeOut = 0;
	$this->sessionId = 0;
    }
    public function serialize(Oarchive &$out, string $tag, ConnectResponse &$v) : int{
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('protocolVersion', $v->passwd);
	$rc = $rc ?: $out->serializeInt('timeOut', $v->timeOut);
	$rc = $rc ?: $out->serializeLong('sessionId', $v->sessionId);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }
    public function unserialize(Iarchive &$in, string $tag, ConnectResponse &$v) : int{
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('protocolVersion', $v->protocolVersion);
	$rc = $rc ?: $in->deserializeInt('timeOut', $v->timeOut);
	$rc = $rc ?: $in->deserializeLong('sessionId', $v->sessionId);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
