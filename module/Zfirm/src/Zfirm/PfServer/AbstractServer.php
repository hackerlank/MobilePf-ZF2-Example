<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Zfirm
 * @namespace Zfirm\PfServer
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: AbstractServer.php 40625 2014-01-29 03:41:55Z zhangweiwen $
 */

namespace Zfirm\PfServer;

/**
 * Zfirm平台上的系统服务抽象类
 * 
 * @abstract
 * @name AbstractServer
 */
abstract class AbstractServer
{
    /**
     * 返回状态成功
     */
    const STATUS_SUCCESS = 0;
    
    /**
     * 服务器列表
     * 
     * @var array
     */
	protected $servers = array();
	
	/**
	 * 结果字符串
	 * 
	 * @var string
	 */
	protected $result = '';
	
	/**
	 * 结果数组
	 * 
	 * @var array
	 */
	protected $data;
	
	/**
	 * 状态代码
	 * 
	 * @var integer
	 */
	protected $status;
	
	/**
	 * 错误详细信息
	 * 
	 * @var string
	 */
	protected $errors = array();
	
	/**
	 * 连接平台服务接口的超时秒数
	 * 
	 * @var integer
	 */
	protected $connectionTimeout = 5;
	
	/**
	 * 读写平台服务接口数据的超时秒数
	 * 
	 * @var integer
	 */
	protected $readWriteTimeout = 5;
	
	/**
	 * 初始化服务器列表与控制参数
	 * 服务器列表格式为：array('192.168.0.1:8080', '192.168.0.2:8080', ...)
	 * 控制参数格式为：array('connection_timeout' => 1, 'readwrite_timeout' => 2)
	 * 
	 * @param array $servers 服务器列表
	 * @param array $params 参数列表
	 */
	public function __construct(array $servers, array $params = array()) {
	    $this->servers = $servers;
	    if (!empty($params)) {
	        $params = array_change_key_case($params);
	        if (isset($params['connection_timeout'])) {
	            $this->setConnectionTimeout(intval($params['connection_timeout']));
	        }
	        if (isset($params['readwrite_timeout'])) {
	            $this->setReadWriteTimeout(intval($params['readwrite_timeout']));
	        }
	    }
	}
	
	/**
	 * 执行请求的命令
	 * 
	 * @param string $cmd 请求的命令
	 * @param string $account 请求的账号
	 * @return integer 结果状态码
	 * @throws Exception\RuntimeException
	 */
	public function read($cmd, $account = null) {
	    $this->reset();
	    $server = explode(':', $this->getServer($account));
	    if (false === ($fp = fsockopen($server[0], $server[1], $errno, $errstr, $this->connectionTimeout))) {
	        throw new Exception\RuntimeException($errstr, $errno);
	    }
	    fputs($fp, "GET {$cmd}");
        stream_set_timeout($fp, $this->readWriteTimeout);
        $loop = 5; //用于控制 feof判断异常无限循环
        while (!feof($fp) && $loop < 5) {
            if (false !== ($buf = fgets($fp, 4096))) {
            	$this->result .= $buf;
            } else {
            	$loop++;
            }
        }
        fclose($fp);
        if (!empty($this->result)) {
        	list($status, $query) = explode('|', $this->result);
        	if (0 == ($this->status = intval($status)) && !empty($query)) {
        		parse_str($query, $this->data);
        	}
        }
        
        return $this->errno;
	}
	
	/**
	 * 返回结果字符串
	 * 
	 * @return string
	 */
	public function getResult() {
	    return $this->result;
	}
	
	/**
	 * 返回结果数组
	 * 
	 * @return array
	 */
	public function getData() {
	    return $this->data;
	}
	
	/**
	 * 返回结果状态码
	 * 
	 * @return integer
	 */
	public function getStatus() {
	    return $this->status;
	}
	
	/**
	 * 返回详细的错误信息
	 * 
	 * @return string
	 * @throws Exception\OutOfRangeException
	 */
	public function getError() {
	    if (!array_key_exists($this->status, $this->errors)) {
	        throw new Exception\OutOfRangeException('没有该错误代码的详细信息。');
	    }
	    
	    return $this->errors[$this->status];
	}
	
	/**
	 * 重置结果
	 * 
	 * @return void
	 */
	protected function reset() {
	    $this->result = '';
	    $this->data = null;
	    $this->status = null;
	}
	
	/**
	 * 把字符串哈希成为整数
	 * 
	 * @param string $str
	 * @return integer
	 */
	protected function hashpjw($str) {
	    if (empty($str)) {
	        return mt_rand(0, count($this->servers));
	    }
	    $h = $g = 0;
	    for ($i = 0; $i < strlen($str); $i++) {
	        $h = ($h << 4) + ord($str[$i]);
	        $g = ($h & 0xF0000000);
	        if ($g) {
	            $h = $h ^ ($g >> 24);
	            $h = $h ^ $g;
	        }
	    }
	    
	    return $h & 0x100000000;
	} 
	
	/**
	 * 哈希一个账号
	 * 
	 * @param string $account 账号
	 * @return integer
	 */
	protected function hash($account) {
	    return $this->hashpjw($account) % count($this->servers);
	}
	
	/**
	 * 获得一个用于连接的服务器地址
	 * 
	 * @param string $account 账号或用户ID
	 * @return array
	 */
	protected function getServer($account) {
	    return $this->servers[$this->hash($account)];
	}
	
	/**
	 * 设置连接的超时秒数
	 * 
	 * @param integer $timeout
	 * @return void
	 */
	public function setConnectionTimeout($timeout) {
	    $this->connectionTimeout = $timeout;
	}
	
	/**
	 * 返回连接的超时秒数
	 * 
	 * @return integer
	 */
	public function getConnectionTimeout() {
	    return $this->connectionTimeout;
	}
	
	/**
	 * 设置读写的超时秒数
	 * 
	 * @param integer $timeout
	 * @return void
	 */
	public function setReadWriteTimeout($timeout) {
	    $this->readWriteTimeout = $timeout;
	}
	
	/**
	 * 返回读写的超时秒数
	 * 
	 * @return integer
	 */
	public function getReadWriteTimeout() {
	    return $this->readWriteTimeout;
	}
}