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

class Oarchive {
    const NEGONE = -1;

    public $priv;
    public function __construct() {
	defined('BIG_ENDIAN') || define('BIG_ENDIAN', pack('L', 1) === pack('N', 1));
	$this->priv = new Buff_struct();
    }
    public function startRecord(string $tag) : int {
	return $this->oaStartRecord($tag);
    }
    protected function oaStartRecord(string $tag) : int {
	return 0;
    }
    public function endRecord(string $tag) : int {
	return $this->oaEndRecord($tag);
    }
    protected function oaEndRecord(string $tag) : int {
	return 0;
    }
    /**
     * 序列化int32_t数据
     * @param string $tag   不清楚具体的作用
     * @param int $d    
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function serializeInt(string $tag, int $d) : int {
	return $this->oaSerializeInt($tag, $d);
    }
    protected function oaSerializeInt(string $tag, int $d) : int {
	$priv = &$this->priv;
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
     * @param int $d    
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function serializeLong(string $tag, int $d) : int {
	return $this->oaSerializeLong($tag, $d);
    }
    protected function oaSerializeLong(string $tag, int $d) : int {
	$priv = &$this->priv;
	//如果数据长度不够，增加长度
	if ($priv->len - $priv->off < Sizeof::INT32_T) {
	    Buff_struct::resizeBuffer($priv, $priv->len + Sizeof::INT32_T);
	}
	//使用N模式，发出网络字节序列的数字
	$priv->buffer .= pack('Q', $d);
	$priv->off += Sizeof::INT64_T;
	return 0;
    }
    public function startVector(string $tag, int $count) : int {
	return $this->oaStartVector($tag, $count);
    }
    protected function oaStartVector(string $tag, int $count) : int {
	return $this->oaSerializeInt($tag, $count);
    }
    public function endVector(string $tag) : int {
	return $this->oaEndVector($tag);
    }
    protected function oaEndVector(string $tag) : int {
	return 0;
    }
    /**
     * 序列化int32_t数据
     * @param string $tag   不清楚具体的作用
     * @param int $d        
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function serializeBool(string $tag, int $d) : int {
	return $this->oaSerializeBool($tag, $d);
    }
    protected function oaSerializeBool(string $tag, int $d) : int {
	$priv = &$this->priv;
	//如果数据长度不够，增加长度
	if ($priv->len - $priv->off < Sizeof::INT32_T) {
	    Buff_struct::resizeBuffer($priv, $priv->len + Sizeof::INT32_T);
	}
	//使用chr将数字0或1转换为ASCII的/0或/1
	$priv->buffer .= $d == 0 ? chr(0) : chr(1);
	$priv->off += Sizeof::INT32_T;
	return 0;
    }
    /**
     * 序列化buffer数据
     * @param string $name   不清楚具体的作用
     * @param Buffer $b    
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function serializeBuffer(string $name, Buffer $b) : int {
	return $this->oaSerializeBuffer($name, $b);
    }
    protected function oaSerializeBuffer(string $name, Buffer $b) : int {
	$priv = &$this->priv;
	if (!$b->buff) {
	    return $this->oaSerializeInt('len', self::NEGONE);
	}
	$rc = $this->oaSerializeInt('len', $b->len);
	if ($rc < 0) {
	    return $rc;
	}
	//如果数据长度不够，增加长度
	if ($priv->len - $priv->off < $b->len) {
	    Buff_struct::resizeBuffer($priv, $priv->len + $b->len);
	}
	//讲buff加入buffer
	$priv->buffer .= $b->buff;
	$priv->off += $b->len;
	return 0;
    }
    /**
     * 序列化字符串数据
     * @param string $name   不清楚具体的作用
     * @param string $s    
     * @return int	    直接返回0
     * @throws ZookeeperException
     */
    public function serializeString(string $name, string $s) : int {
	return $this->oaSerializeString($name, $s);
    }
    protected function oaSerializeString(string $name, string $s) : int {
	$priv = &$this->priv;
	$len = strlen($s);
	//这里不能根据$s来判断，因为'0'也是false，只能根据字符串的长度
	if (!$len) {
	    $this->oaSerializeInt('len', self::NEGONE);
	    return 0;
	}
	$rc = $this->oaSerializeInt('len', $len);
	if ($rc < 0) {
	    return $rc;
	}
	//如果数据长度不够，增加长度
	if ($priv->len - $priv->off < $len) {
	    Buff_struct::resizeBuffer($priv, $priv->len + $len);
	}
	//讲buff加入buffer
	$priv->buffer .= $s;
	$priv->off += $len;
	return 0;
    }
}
