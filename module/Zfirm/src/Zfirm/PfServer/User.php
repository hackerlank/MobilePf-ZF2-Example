<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Zfirm
 * @namespace Zfirm\PfServer
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: User.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace Zfirm\PfServer;

/**
 * 用户服务，封装了Zfirm平台用户信息的请求服务。
 * 
 * @name User
 */
class User extends AbstractServer
{
    /**
     * 
     * @var array
     */
    protected $errors = array(
    	-1 => '未知错误', 
        1 => '操作失败', 
        2 => '账号已经存在', 
        3 => '输入的账号不是有效的电子邮件地址', 
        4 => '输入的email地址不是有效的电子邮件地址', 
        5 => '昵称包含非法字符', 
        6 => '数字密码无效', 
        7 => '真实姓名包含非法字符', 
        8 => '性别错误', 
        9 => '生日错误', 
        10 => '固定联系电话格式错误', 
        11 => '地址包含非法字符', 
        12 => '身份证号码包含非法字符', 
        13 => '移动电话包含非法字符', 
        14 => '移动电话类型填错', 
        15 => '账号不存在', 
        16 => '邮编包含非法字符', 
        20 => '密码错误', 
        21 => '密码少于6位', 
        22 => '超级密码错误', 
        23 => '令牌错误'
    );
    
    /**
     * 
     * @var string
     */
    protected $key = '1234567890';
    
    /**
	 * 初始化服务器列表与控制参数
	 * 服务器列表格式为：array('192.168.0.1:8080', '192.168.0.2:8080', ...)
	 * 控制参数格式为：array('connection_timeout' => 1, 'readwrite_timeout' => 2, 'key' => 'xxxxxxx')
	 * 
	 * @param array $servers 服务器列表
	 * @param array $params 参数列表
	 */
    public function __construct($servers, $params = array()) {
        parent::__construct($servers, $params);
        
        if (isset($params['key'])) {
            $this->key = $params['key'];
        }
    }
    
    /**
     * 登录验证，成功则返回用户信息。
     * 
     * @param string $account 账号名
     * @param array $data 账号信息
     * @return false|array
     */
    public function login($account, array $data) {
        if (is_string($account) && !empty($account)) {
            $data['account'] = $account;
            $query = urldecode(http_build_query($data));
            $cmd = "/login?{$data}";
            if (self::STATUS_SUCCESS == $this->read($cmd, $account)) {
                return $this->getData();
            }
        }
        
        return false;
    }
    
    /**
     * 用密码登陆，登陆成功返回账号信息
     * 
     * @param string $account 账号名
     * @param string $passwd 密码
     * @param string $ip IP地址
     * @return false|array
     */
    public function loginPasswd($account, $passwd, $ip = '') {
        $data = array(
            'passwd' => $passwd
        );
        if (1 == preg_match("/^[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$/", $ip)) {
            $data['ip'] = $ip;
        }
        
        return $this->login($account, $data);
    }
    
    /**
     * 用令牌登陆，登陆成功返回账号信息
     * 
     * @param string $account 账号名
     * @param string $token 密码
     * @param string $ip IP地址
     * @return false|array
     */
    public function loginToken($account, $token, $ip = '') {
        $data = array(
            'token' => $token
        );
        if (1 == preg_match("/^[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$/", $ip)) {
            $data['ip'] = $ip;
        }
        
        return $this->login($account, $data);
    }
    
    /**
     * 第三方账号登陆获取登陆token
     * 
     * @param string $account 账号名
     * @param string $ip IP地址
     * @param string $screenname 昵称
     * @return false|array
     */
    public function setToken($account, $ip, $screenname = '') {
        if (!empty($account) && !empty($ip)) {
            $ukey = $this->getUkey($account, $ip);
            $cmd = "/settoken?account={$account}&ukey={$ukey}&ip={$ip}&screenname={$screenname}";
            if (self::STATUS_SUCCESS == $this->read($cmd, $account)) {
                return $this->getData();
            }
        }
        
        return false;
    }
    
    /**
     * 产生settoken服务所需的ukey参数值
     * 
     * @param string $account 账号名
     * @param string $ip IP地址
     * @return string
     */
    protected function getUkey($account, $ip) {
        return md5("{$account}|{$this->key}|{$ip}");
    }
}