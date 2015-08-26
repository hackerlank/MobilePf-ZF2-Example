<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package W91
 * @namespace W91\Auth
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: Http.php 40848 2014-02-20 12:58:39Z zhangweiwen $
 */

namespace W91\Auth;

use Platform\Response\Failure;
use Platform\Auth\Event as AuthEvent;
use Platform\Auth\HttpAbstract;
use Zend\Http\PhpEnvironment\RemoteAddress;
use W91\Sdk\Sdk;

/**
 * 91无线的身份验证类
 * 
 * @name Http
 */
class Http extends HttpAbstract implements HttpInterface
{
    /*
     * 91无线接入文档中的appId
     * 
     * @var string
     */
    protected $appId;
    
    /**
     * 91无线接入文档中的appKey
     * 
     * @var string
     */
    protected $appKey;
    
    /**
     * 初始化91无线的参数
     * 
     * @param string $appId 91无线接入文档中的appId
     * @param string $appKey 91无线接入文档中的appKey
     */
    public function __construct($appId, $appKey) {
        $this->appId = $appId;
        $this->appKey = $appKey;
    }
    
    /**
     * 执行91无线的数据验证
     * 
     * @param AuthEvent $e
     * @return mixed
     */
    public function onVerify(AuthEvent $e) {
        $request = $e->getQuery();
        
        if (isset($request['uin']) && isset($request['sessionid'])) {
            $user = $this->checkSessionId($request['Uin'], $request['sessionid']);
            if (!isset($userinfo['ErrorCode']) || 1 != intval($user['ErrorCode'])) {
                return new Failure('获取用户信息失败。', $user);
            }
            $e->setResult($user);
        } else {
            return new Failure('Uin参数或sessionId参数不存在。', $request);
        }
        
        return $e->getResult();
    }
    
    /**
     * 执行Zfirm用户平台的Token验证
     * 
     * @param AuthEvent $e
     * @return mixed
     */
    public function onToken(AuthEvent $e) {
        $user = $e->getResult();
        
        $e->setParam('account', "_jiuyao_m_{$user['Uin']}");
        $e->setParam('ip', (new RemoteAddress())->getIpAddress());
        $e->setParam('nickname', iconv('utf-8', 'gbk', $user['UserName']));
        
        $user = $this->getUserService();
        if (false === ($zfirm = $user->setToken($e->getParam('account'), $e->getParam('ip'), $e->getParam('nickname')))) {
            return new Failure('获取Zfirm用户平台登陆Token失败。');
        }
        $e->setZfirm($zfirm);
        
        return $e->getZfirm();
    }
    
    /**
     * 
     * @see HttpInterface::checkSessionId()
     */
    public function checkSessionId($uin, $sessionId) {
        $sdk = new Sdk($this->appId, $this->appKey);
        
        return $sdk->check_user_login($uin, $sessionId);
    }
}