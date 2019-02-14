<?php
namespace Spool\Zookeeper\Client\Generated;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author 陈浩波
 */
interface Base extends \Serializable {
    public function serialize() : string;
    public function unserialize(string $str) : Mixed;
    public function __toString() : string;
    public function __invoke() : void;
}
