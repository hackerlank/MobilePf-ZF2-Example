<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Platform
 * @namespace Platform\Log
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: AuthLoggerListenerAbstract.php 40866 2014-02-21 10:43:11Z zhangweiwen $
 */

namespace Platform\Log;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Log\Logger;
use Platform\Auth\Event as AuthEvent;

/**
 * 关于身份验证的日志处理监听器抽象类
 * 
 * @abstract
 * @name AuthLoggerListenerAbstract
 */
abstract class AuthLoggerListenerAbstract implements ListenerAggregateInterface
{
    /**
     * 注入ListenerAggregateTrait
     * 
     * @uses \Zend\EventManager\ListenerAggregateTrait
     */
    use ListenerAggregateTrait;
    
    /**
     * 
     * @var Logger
     */
    protected $logger;
    
    /**
     * 初始化日志处理器
     * 
     * @param Logger $logger
     */
    public function __construct(Logger $logger) {
        $this->logger = $logger;        
    }
    
    /**
     * 记录验证用户信息之后的日志
     * 
     * @abstract
     * @param AuthEvent $e
     * @return void
     */
    abstract public function onVerifyPost(AuthEvent $e);
    
    /**
     * 记录验证用户信息完毕后的日志
     * 
     * @abstract
     * @param AuthEvent $e
     * @return void
     */
    abstract public function onTokenPost(AuthEvent $e);
    
    /**
     * 记录验证用户信息失败的日志
     * 
     * @abstract
     * @param AuthEvent $e
     * @return void
     */
    abstract public function onFailure(AuthEvent $e);
    
    /**
     * 注册事件监听器，由事件管理器回调。
     * 
     * @param EventManagerInterface $eventManager
     * @return AuthLoggerListener
     */
    public function attach(EventManagerInterface $eventManager) {
        $this->listeners[] = $eventManager->attach(AuthEvent::EVENT_VERIFY_POST, array($this, 'onVerifyPost'));
        $this->listeners[] = $eventManager->attach(AuthEvent::EVENT_TOKEN_POST, array($this, 'onTokenPost'));
        $this->listeners[] = $eventManager->attach(AuthEvent::EVENT_FAILURE, array($this, 'onFailure'));
        
        return $this;
    }
}