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
 * Description of Stat
 *
 * @author 陈浩波
 */
class StatPersisted{
    public $czxid;		//int64_t
    public $mzxid;		//int64_t
    public $ctime;		//int64_t
    public $mtime;		//int64_t
    public $version;		//int32_t
    public $cversion;		//int32_t
    public $aversion;		//int32_t
    public $ephemeralOwner;  	//int64_t
    public $pzxid;		//int64_t
    public function serialize(Oarchive &$out, string $tag, StatPersisted &$v) : int{
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeLong('czxid', $v->czxid);
	$rc = $rc ?: $out->serializeLong('mzxid', $v->mzxid);
	$rc = $rc ?: $out->serializeLong('ctime', $v->ctime);
	$rc = $rc ?: $out->serializeLong('mtime', $v->mtime);
	$rc = $rc ?: $out->serializeInt('version', $v->version);
	$rc = $rc ?: $out->serializeInt('cversion', $v->cversion);
	$rc = $rc ?: $out->serializeInt('aversion', $v->aversion);
	$rc = $rc ?: $out->serializeLong('ephemeralOwner', $v->ephemeralOwner);
	$rc = $rc ?: $out->serializeLong('pzxid', $v->pzxid);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }
    public function unserialize(Iarchive &$in, string $tag, StatPersisted &$v) : int{
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeLong('czxid', $v->czxid);
	$rc = $rc ?: $in->deserializeLong('mzxid', $v->mzxid);
	$rc = $rc ?: $in->deserializeLong('ctime', $v->ctime);
	$rc = $rc ?: $in->deserializeLong('mtime', $v->mtime);
	$rc = $rc ?: $in->deserializeInt('version', $v->version);
	$rc = $rc ?: $in->deserializeInt('cversion', $v->cversion);
	$rc = $rc ?: $in->deserializeInt('aversion', $v->aversion);
	$rc = $rc ?: $in->deserializeLong('ephemeralOwner', $v->ephemeralOwner);
	$rc = $rc ?: $in->deserializeLong('pzxid', $v->pzxid);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
