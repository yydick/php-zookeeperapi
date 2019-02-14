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
class Oarchive {
    public $priv;
    public function __construct() {
	$this->priv = new Buff_struct();
    }
    public function oaStartRecord(string $tag) : int {
	return 0;
    }
    public function oaEndRecord(string $tag) : int {
	return 0;
    }
    /**
     * 序列化int32_t数据
     * @param string $tag   不清楚具体的作用
     * @param int $count    通过引用返回
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function oaSerializeInt(string $tag, int $d) : int {
	$priv = $this->priv;
	//如果数据长度不够，增加长度
	if ($priv->len - $priv->off < Sizeof::INT32_T) {
	    Buff_struct::resizeBuffer($priv, $priv->len + Sizeof::INT32_T);
	}
	//使用N模式，发出网络字节序列的数字
	$priv->buffer .= pack('L', $d);
	$priv->off += Sizeof::INT32_T;
	return 0;
    }
    /**
     * 序列化int64_t数据
     * 警告！
     * 这里默认了使用的是64位系统，PHP的int也默认为8个字节。
     * 32位系统的兼容性以后再处理
     * @param string $tag   不清楚具体的作用
     * @param int $count    通过引用返回
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function oaSerializeLong(string $tag, int $d) : int {
	$priv = $this->priv;
	//如果数据长度不够，增加长度
	if ($priv->len - $priv->off < Sizeof::INT32_T) {
	    Buff_struct::resizeBuffer($priv, $priv->len + Sizeof::INT32_T);
	}
	//使用N模式，发出网络字节序列的数字
	$priv->buffer .= pack('Q', $d);
	$priv->off += Sizeof::INT64_T;
	return 0;
    }
    public function oaStartVector(string $tag, int $count) : int {
	return $this->oaSerializeInt($tag, $count);
    }
    public function oaEndVector(string $tag) : int {
	return 0;
    }
    /**
     * 序列化int32_t数据
     * @param string $tag   不清楚具体的作用
     * @param int $count    通过引用返回
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function oaSerializeBool(string $tag, int $d) : int {
	$priv = $this->priv;
	//如果数据长度不够，增加长度
	if ($priv->len - $priv->off < Sizeof::INT32_T) {
	    Buff_struct::resizeBuffer($priv, $priv->len + Sizeof::INT32_T);
	}
	//使用N模式，发出网络字节序列的数字
	$priv->buffer .= $d == 0 ? chr(0) : chr(1);
	$priv->off += Sizeof::INT32_T;
	return 0;
    }
}
