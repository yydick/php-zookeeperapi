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
use Spool\Zookeeper\Client\Generated\AclVector;
/**
 * Description of QuorumAuthPacket
 *
 * @author 陈浩波
 */
class QuorumAuthPacket {

    public $path;	      //string
    public $data;	      //obj buffer
    public $acl;	      //AclVector
    public $ephemeral;	      //int32_t

    public function __construct() {
	$this->path = '';
	$this->data = new Buffer();
	$this->acl = new AclVector();
	$this->ephemeral = 0;
    }
    public function serialize(Oarchive &$out, string $tag, QuorumAuthPacket &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeString('path', $v->path);
	$rc = $rc ?: $out->serializeBuffer('data', $v->data);
	$rc = $rc ?: $this->acl->serialize($out, 'acl', $v->acl);
	$rc = $rc ?: $out->serializeInt('ephemeral', $v->ephemeral);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, QuorumAuthPacket &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeString('path', $v->path);
	$rc = $rc ?: $in->deserializeBuffer('data', $v->data);
	$rc = $rc ?: $this->acl->unserialize($in, 'acl', $v->acl);
	$rc = $rc ?: $in->deserializeInt('ephemeral', $v->ephemeral);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
