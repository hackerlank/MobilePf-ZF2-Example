<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Qihu
 * @namespace Qihu\Listener
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: AuthLoggerListener.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace Qihu\Listener;

use Platform\Auth\Event as AuthEvent;
use Platform\Log\AuthLoggerListenerAbstract;

/**
 * 日志处理监听器，触发奇虎(360)验证过程的日志事件。
 * 
 * @name AuthLoggerListener
 */
class AuthLoggerListener extends AuthLoggerListenerAbstract
{
    /**
     * 验证奇虎(360)用户信息之后，记录日志。
     * 
     * @param AuthEvent $e
     * @return void
     */
    public function onVerifyPost(AuthEvent $e) {
        $request = $e->getQuery();
        
        $this->logger->info("360, code: {$request['code']}");
    }
    
    /**
     * 验证Zfirm用户平台用户信息之后，记录日志。
     * 
     * @param AuthEvent $e
     * @return void
     */
    public function onTokenPost(AuthEvent $e) {
        $result = $e->getResult();
        $zfirm = $e->getZfirm();
        $params = $e->getParams();
        
        $this->logger->info("360, access_token: {$params['access_token']}, account: {$params['account']}, ip: {$params['ip']}, token: {$zfirm['token']}");
    }
    
    /**
     * 记录验证用户信息失败的日志
     * 
     * @param AuthEvent $e
     * @return void
     */
    public function onFailure(AuthEvent $e) {
        $failure = $e->getFailure();
        
        $this->logger->warn($failure->getReason(), (array) $failure->getResult());
    }
}