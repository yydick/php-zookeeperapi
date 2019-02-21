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
 * Description of ExistsRequest
 *
 * @author 陈浩波
 */
class ExistsRequest {

    public $path;	      //string
    public $watch;	      //int32_t

    public function __construct() {
	$this->path = '';
	$this->watch = 0;
    }
    public function serialize(Oarchive &$out, string $tag, ExistsRequest &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeString('path', $v->path);
	$rc = $rc ?: $out->serializeInt('watch', $v->watch);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, ExistsRequest &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeString('path', $v->path);
	$rc = $rc ?: $in->deserializeInt('watch', $v->watch);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
