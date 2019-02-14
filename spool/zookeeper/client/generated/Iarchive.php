<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Generated;

use Spool\Zookeeper\Client\Generated\Buff_struct;
use Spool\Zookeeper\Client\Generated\Sizeof;
/**
 * Description of iarchive
 *
 * @author 陈浩波
 */
class Iarchive {
    public $priv;
    public function __construct() {
	$this->priv = new Buff_struct();
    }
    public function iaStartRecord(string $tag) : int {
	return 0;
    }
    public function iaEndRecord(string $tag) : int {
	return 0;
    }
    /**
     * 反序列化int32_t数据
     * @param string $tag   不清楚具体的作用
     * @param int $count    通过引用返回
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function iaDeserializeInt(string $tag, int &$count) : int {
	$priv = $this->priv;
	//如果数据长度不够，返回错误
	if ($priv->len - $priv->off < Sizeof::INT32_T) {
	    return -E2BIG;
	}
	//使用L模式，返回主机字节序列的数字
	$count = unpack('L', substr($priv->buffer, $priv->off, Sizeof::INT32_T));
	return 0;
    }
    /**
     * 反序列化int64_t数据
     * @param string $tag   不清楚具体的作用
     * @param int $count    通过引用返回
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function iaDeserializeLong(string $tag, int &$count) : int {
	$priv = $this->priv;
	//如果数据长度不够，返回错误
	if ($priv->len - $priv->off < Sizeof::INT32_T) {
	    return -E2BIG;
	}
	//使用L模式，返回主机字节序列的数字
	$count = unpack('L', substr($priv->buffer, $priv->off, Sizeof::INT64_T));
	return 0;
    }
}
