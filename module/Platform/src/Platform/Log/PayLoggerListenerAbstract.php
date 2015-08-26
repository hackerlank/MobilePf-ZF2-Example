<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Platform
 * @namespace Platform\Log
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: PayLoggerListenerAbstract.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace Platform\Log;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Log\Logger;
use Platform\Pay\Event as PayEvent;

/**
 * 关于支付的日志处理监听器抽象类。
 *
 * @abstract
 * @name AuthLoggerListenerAbstract
 */
abstract class PayLoggerListenerAbstract implements ListenerAggregateInterface
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
     * 记录验证用户信息失败的日志
     *
     * @abstract
     * @param PayEvent $e
     * @return void
    */
    abstract public function onFailure(PayEvent $e);
    
    /**
     * 注册事件
     *
     * @param EventManagerInterface $eventManager
     * @return AuthLoggerListener
    */
    public function attach(EventManagerInterface $eventManager) {
    	$this->listeners[] = $eventManager->attach(PayEvent::EVENT_FAILURE, array($this, 'onFailure'));
    
    	return $this;
    }
}