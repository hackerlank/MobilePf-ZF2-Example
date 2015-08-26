<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Qihu
 * @namespace Qihu\Auth
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: Http.php 40848 2014-02-20 12:58:39Z zhangweiwen $
 */

namespace Qihu\Auth;

use Qihu\Sdk\QOAuth2;
use Qihu\Sdk\QClient;
use Platform\Response\Failure;
use Platform\Auth\Event as AuthEvent;
use Platform\Auth\HttpAbstract;
use Zend\Http\PhpEnvironment\RemoteAddress;

/**
 * 奇虎(360)的身份验证类
 * 
 * @name Http
 */
class Http extends HttpAbstract implements HttpInterface
{
    /*
     * 360接入文档中的app_key
     * 
     * @var string
     */
    protected $appKey;
    
    /**
     * 360接入文档中的app_secret
     * 
     * @var string
     */
    protected $appSecret;
    
    /**
     * 初始化360的参数
     * 
     * @param string $appKey 360接入文档中的app_key
     * @param string $appSecret 360接入文档中的app_secret
     */
    public function __construct($appKey, $appSecret) {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
    }
    
    /**
     * 执行奇虎(360)数据验证
     * 
     * @param AuthEvent $e
     * @return array|Failure
     */
    public function onVerify(AuthEvent $e) {
        $request = $e->getQuery();
        
        if (isset($request['code']) && !empty($request['code'])) {
            $token = $this->getAccessToken($request['code']);
            if (!isset($token['access_token']) || empty($token['access_token'])) {
                return new Failure('获取access token失败。', $token);
            }
            $e->setParam('access_token', $token['access_token']);
            
            $user = $this->getUserInfo($token['access_token']);
            if (!isset($user['id']) || empty($user['id'])) {
                return new Failure('获取用户信息失败。', $user);
            }
            $e->setResult($user);
        } else {
            return new Failure('code参数不存在。', $request);
        }
        
        return $e->getResult();
    }
    
    /**
     * 执行Zfirm用户平台的数据验证，获取Token。
     * 
     * @param AuthEvent $e
     * @return mixed
     */
    public function onToken(AuthEvent $e) {
        $user = $e->getResult();
        
        $e->setParam('account', "_qihu_m_{$user['id']}");
        $e->setParam('ip', (new RemoteAddress())->getIpAddress());
        $e->setParam('nickname', iconv('utf-8', 'gbk', $user['name']));
        
        $user = $this->getUserService();
        if (false === ($zfirm = $user->setToken($e->getParam('account'), $e->getParam('ip'), $e->getParam('nickname')))) {
            return new Failure('获取Zfirm用户平台登陆Token失败。');
        }
        $e->setZfirm($zfirm);
        
        return $e->getZfirm();
    }
    
	/**
	 * 
	 * @see HttpInterface::getAccessToken()
	 */
	public function getAccessToken($code) {
	    $oauth = new QOAuth2($this->appKey, $this->appSecret, '');
	    
		return $oauth->getAccessTokenByCode($code, 'oob');
	}

	/* 
	 * 
	 * @see HttpInterface::getUserInfo()
	 */
	public function getUserInfo($accessToken) {
		$client = new QClient($this->appKey, $this->appSecret, $accessToken);
		
		return $client->userMe();
	}

	/* 
	 * 
	 * @see HttpInterface::refreshAccessToken()
	 */
	public function refreshAccessToken($refreshToken) {
		$oauth = new QOAuth2($this->appKey, $this->appSecret, '');
		
		return $oauth->getAccessTokenByRefreshToken($refreshToken, 'basic');
	}

	/* 
	 * 
	 * @see HttpInterface::getAccessTokenInfo()
	 */
	public function getAccessTokenInfo($accessToken) {
		$client = new QClient($this->appKey, $this->appSecret, $accessToken);
		
		return $client->tokenInfo();
	}
}