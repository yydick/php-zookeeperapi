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
 * Description of Acl
 *
 * @author 陈浩波
 */
class Acl {

    public $perms;	      //int32
    public $id;		      //obj Id

    public function __construct() {
	$this->perms = 0;
	$this->id = new Id();
    }
    public function serialize(Oarchive &$out, string $tag, Acl &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('perms', $v->perms);
	$rc = $rc ?: $this->id->serialize($out, 'id', $v->id);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, Acl &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('perms', $v->perms);
	$rc = $rc ?: $this->id->unserialize($in, 'id', $v->id);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
