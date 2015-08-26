<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package W91
 * @namespace W91\Listener
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: AuthLoggerListener.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace W91\Listener;

use Platform\Auth\Event as AuthEvent;
use Platform\Log\AuthLoggerListenerAbstract;

/**
 * 日志处理监听器，触发91无线验证过程的日志事件。
 * 
 * @name AuthLoggerListener
 */
class AuthLoggerListener extends AuthLoggerListenerAbstract
{
    /**
     * 记录验证用户信息之后的日志
     * 
     * @param AuthEvent $e
     * @return void
     */
    public function onVerifyPost(AuthEvent $e) {
        $request = $e->getQuery();
        
        $this->logger->info("91, uin: {$request['uin']}, sessionid: {$request['sessionid']}");
    }
    
    /**
     * 记录验证用户信息完毕后的日志
     * 
     * @param AuthEvent $e
     * @return void
     */
    public function onTokenPost(AuthEvent $e) {
        $result = $e->getResult();
        $zfirm = $e->getZfirm();
        $params = $e->getParams();
        
        $this->logger->info("91, username: {$result['UserName']}, account: {$params['account']}, ip: {$params['ip']}, token: {$zfirm['token']}");
    }
    
    /**
     * 记录验证用户信息失败的日志
     * 
     * @param AuthEvent $e
     * @return void
     */
    public function onFailure(AuthEvent $e) {
        $failure = $e->getFailure();
        
        $this->logger->warn($failure->getReason(), $failure->getResult());
    }
}