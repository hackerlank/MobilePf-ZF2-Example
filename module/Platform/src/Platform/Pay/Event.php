<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Platform
 * @namespace Platform\Pay
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: Event.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace Platform\Pay;

use Zend\EventManager\Event as ZendEvent;
use Platform\Response\Failure;

/**
 * 支付事件，由事件管理器触发。
 *
 * @name Event
 */
class Event extends ZendEvent
{
    /**#@+
     * 
     * 支付事件名称
     */
    const EVENT_FAILURE = 'failure';         // 支付失败
    /**#@-*/
    
    /**
     * 移动客户端提交的请求参数
     * 
     * @var array
     */
    protected $query;
    
    /**
     * 失败响应
     * 
     * @var Failure
     */
    protected $failure;
    
    /**
     * 返回移动客户端提交的请求参数
     * 
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }
    
    /**
     * 设置移动客户端提交的请求参数
     * 
     * @param array $query
     * @return Event
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * 返回失败响应对象
     * 
     * @return Failure
     */
    public function getFailure()
    {
        return $this->failure;
    }

    /**
     * 设置失败响应对象
     * 
     * @param Failure $failure
     * @return Event
     */
    public function setFailure(Failure $failure)
    {
        $this->failure = $failure;
        return $this;
    }
}