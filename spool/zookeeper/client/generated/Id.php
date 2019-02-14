<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Generated;

use Spool\Zookeeper\Client\Generated\Base;
/**
 * Description of Id
 *
 * @author 陈浩波
 */
class Id {
    public $scheme;             //string
    public $id;                 //string

    public function serialize(): string {
	
    }

    public function unserialize(string $str): Mixed {
	
    }

    public function __toString(): string {
	
    }

    public function __invoke(string $scheme, string $id): void {
	$this->scheme = $scheme;
	$this->id = $id;
    }
}
