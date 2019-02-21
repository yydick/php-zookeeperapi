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
 * Description of FileHeader
 *
 * @author 陈浩波
 */
class FileHeader {

    public $magic;	      //int32_t
    public $version;	      //int32_t
    public $dbid;	      //int64_t

    public function __construct() {
	$this->magic = 0;
	$this->version = 0;
	$this->dbid = 0;
    }
    public function serialize(Oarchive &$out, string $tag, FileHeader &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('magic', $v->magic);
	$rc = $rc ?: $out->serializeInt('version', $v->version);
	$rc = $rc ?: $out->serializeLong('dbid', $v->dbid);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, FileHeader &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('magic', $v->magic);
	$rc = $rc ?: $in->deserializeInt('version', $v->version);
	$rc = $rc ?: $in->deserializeLong('dbid', $v->dbid);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
