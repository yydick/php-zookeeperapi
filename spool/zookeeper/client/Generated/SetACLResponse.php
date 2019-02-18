<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Generated;


use Spool\Zookeeper\Client\Classes\Oarchive;
use Spool\Zookeeper\Client\Classes\Iarchive;
use Spool\Zookeeper\Client\Generated\Stat;

/**
 * Description of SetACLResponse
 *
 * @author 陈浩波
 */
class SetACLResponse {

    public $stat;	      //obj Stat

    public function __construct() {
	$this->stat = new Stat();
    }
    public function serialize(Oarchive &$out, string $tag, SetACLResponse &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $this->stat->serialize($out, 'stat', $v->stat);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, SetACLResponse &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $this->stat->unserialize($in, 'stat', $v->stat);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
