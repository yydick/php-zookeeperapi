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
use Spool\Zookeeper\Client\Generated\Stat;

/**
 * Description of GetChildren2Response
 *
 * @author 陈浩波
 */
class GetChildren2Response {

    public $children;	      //obj StringVector
    public $stat;	      //obj Stat

    public function __construct() {
	$this->children = new StringVector();
	$this->stat = new Stat();
    }
    public function serialize(Oarchive &$out, string $tag, GetChildren2Response &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $this->children->serialize($out, 'children', $v->children);
	$rc = $rc ?: $this->stat->serialize($out, 'stat', $v->stat);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, GetChildren2Response &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $this->children->unserialize($in, 'children', $v->children);
	$rc = $rc ?: $this->stat->unserialize($in, 'stat', $v->stat);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
