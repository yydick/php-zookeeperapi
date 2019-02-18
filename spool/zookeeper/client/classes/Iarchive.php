<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Classes;

use Spool\Zookeeper\Client\Classes\Buff_struct;
use Spool\Zookeeper\Client\Classes\Buffer;
use Spool\Zookeeper\Client\Classes\Sizeof;
/**
 * Description of iarchive
 *
 * @author 陈浩波
 */
class Iarchive {
    public $priv;
    public function __construct() {
	defined('BIG_ENDIAN') || define('BIG_ENDIAN', pack('L', 1) === pack('N', 1));
	$this->priv = new Buff_struct();
    }
    public function startRecord(string $tag) : int {
	return $this->iaStartRecord($tag);
    }
    protected function iaStartRecord(string $tag) : int {
	return 0;
    }
    public function endRecord(string $tag) : int {
	return $this->iaEndRecord($tag);
    }
    protected function iaEndRecord(string $tag) : int {
	return 0;
    }
    /**
     * 反序列化int32_t数据
     * @param string $tag   不清楚具体的作用
     * @param int $count    通过引用返回
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function deserializeInt(string $tag, int &$count) : int {
	return $this->iaDeserializeInt($tag, $count);
    }
    protected function iaDeserializeInt(string $tag, int &$count) : int {
	$priv = $this->priv;
	//如果数据长度不够，返回错误
	if ($priv->len - $priv->off < Sizeof::INT32_T) {
	    return -E2BIG;
	}
	//如果本机是大端许序，就使用N，否则使用L
	if (BIG_ENDIAN) {
	    $tmpl = 'N';
	} else {
	    $tmpl = 'L';
	}
	$count = unpack($tmpl, substr($priv->buffer, $priv->off, Sizeof::INT32_T))[1];
	$priv->off += Sizeof::INT32_T;
	return 0;
    }
    /**
     * 反序列化int64_t数据
     * @param string $tag   不清楚具体的作用
     * @param int $count    通过引用返回
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function deserializeLong(string $tag, int &$count) : int {
	return $this->iaDeserializeLong($tag, $count);
    }
    protected function iaDeserializeLong(string $tag, int &$count) : int {
	$priv = $this->priv;
	//如果数据长度不够，返回错误
	if ($priv->len - $priv->off < Sizeof::INT64_T) {
	    return -E2BIG;
	}
	//如果本机是大端许序，就使用N，否则使用L
	if (BIG_ENDIAN) {
	    $tmpl = 'J';
	} else {
	    $tmpl = 'Q';
	}
	$count = unpack($tmpl, substr($priv->buffer, $priv->off, Sizeof::INT64_T))[1];
	$priv->off += Sizeof::INT64_T;
	return 0;
    }
    public function startVector(string $tag, int &$count) : int {
	return $this->iaStartVector($tag, $count);
    }
    protected function iaStartVector(string $tag, int &$count) : int {
	return $this->iaDeserializeInt($tag, $count);
    }
    public function endVector(string $tag, int &$count) : int {
	return $this->iaEndVector($tag, $count);
    }
    protected function iaEndVector(string $tag, int &$count) : int {
	return 0;
    }
    /**
     * 反序列化bool数据
     * @param string $tag   不清楚具体的作用
     * @param int $v    通过引用返回
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function deserializeBool(string $tag, int &$v) : int {
	return $this->iaDeserializeBool($tag, $v);
    }
    protected function iaDeserializeBool(string $tag, int &$v) : int {
	$priv = $this->priv;
	//如果数据长度不够，返回错误
	if ($priv->len - $priv->off < 1) {
	    return -E2BIG;
	}
	//使用ord将/0或/1转换为0或1作为false或true
	$v = ord(substr($priv->buffer, $priv->off, 1));
	$priv->off += 1;
	return 0;
    }
    /**
     * 反序列化buffer数据
     * @param string $tag   不清楚具体的作用
     * @param buffer $b    通过引用返回
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function deserializeBuffer(string $tag, Buffer &$b) : int {
	return $this->iaDeserializeBuffer($tag, $b);
    }
    protected function iaDeserializeBuffer(string $tag, Buffer &$b) : int {
	$priv = $this->priv;
	$rc = $this->iaDeserializeInt('len', $b->len);
	if ($rc < 0) {
	    return $rc;
	}
	//如果数据长度不够，返回错误
	if ($priv->len - $priv->off < $b->len) {
	    return -E2BIG;
	}
	if ($b->len == -1) {
	    $b->buff = '';
	    return $rc;
	}
	$b->buff = substr($priv->buffer, $priv->off, $b->len);
	$priv->off += $b->len;
	return 0;
    }
    /**
     * 反序列化string数据
     * @param string $tag   不清楚具体的作用
     * @param string $s    通过引用返回
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function deserializeString(string $tag, string &$s) : int {
	return $this->iaDeserializeString($tag, $s);
    }
    protected function iaDeserializeString(string $tag, string &$s) : int {
	$priv = $this->priv;
	$len = 0;
	$rc = $this->iaDeserializeInt('len', $b->len);
	if ($rc < 0) {
	    return $rc;
	}
	//如果数据长度不够，返回错误
	if ($priv->len - $priv->off < $b->len) {
	    return -E2BIG;
	}
	if ($b->len == -1) {
	    $b->buff = '';
	    return $rc;
	}
	$b->buff = substr($priv->buffer, $priv->off, $b->len);
	$priv->off += $b->len;
	return 0;
    }
}
