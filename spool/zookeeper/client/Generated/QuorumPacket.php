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
use Spool\Zookeeper\Client\Generated\IdVector;
/**
 * Description of QuorumPacket
 *
 * @author 陈浩波
 */
class QuorumPacket {

    public $type;	      //int32_t
    public $zxid;	      //int64_t
    public $data;	      //obj buffer
    public $authinfo;	      //obj IdVector

    public function __construct() {
	$this->type = 0;
	$this->zxid = 0;
	$this->data = new Buffer();
	$this->authinfo = new IdVector();
    }
    public function serialize(Oarchive &$out, string $tag, QuorumPacket &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('type', $v->type);
	$rc = $rc ?: $out->serializeLong('zxid', $v->zxid);
	$rc = $rc ?: $out->serializeBuffer('data', $v->data);
	$rc = $rc ?: $this->authinfo->serialize($out, 'authinfo', $v->authinfo);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, QuorumPacket &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('type', $v->type);
	$rc = $rc ?: $in->deserializeLong('zxid', $v->zxid);
	$rc = $rc ?: $in->deserializeBuffer('data', $v->data);
	$rc = $rc ?: $this->authinfo->unserialize($in, 'authinfo', $v->authinfo);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
