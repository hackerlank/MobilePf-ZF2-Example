<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Platform
 * @namespace Platform\Auth
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: Event.php 40848 2014-02-20 12:58:39Z zhangweiwen $
 */

namespace Platform\Auth;

use Zend\EventManager\Event as ZendEvent;
use Platform\Response\Failure;

/**
 * 身份验证事件，由事件管理器触发。
 *
 * @name Event
 */
class Event extends ZendEvent
{
    /**#@+
     * 
     * 身份验证事件名称
     */
    const EVENT_VERIFY = 'verify';           // 第三方数据验证
    const EVENT_VERIFY_POST = 'verify.post'; // 第三方数据验证完毕
    const EVENT_TOKEN = 'token';             // 产生Zfirm用户平台的Token
    const EVENT_TOKEN_POST = 'token.post';   // 产生Zfirm用户平台的Token完毕
    const EVENT_FAILURE = 'failure';         // 身份验证失败
    /**#@-*/
    
    /**
     * 移动客户端提交的请求参数
     * 
     * @var array
     */
    protected $query;
    
    /**
     * 第三方平台返回结果
     * 
     * @var array
     */
    protected $result;
    
    /**
     * Zfirm用户平台返回结果
     * 
     * @var array
     */
    protected $zfirm;
    
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
     * 返回第三方平台结果
     * 
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * 设置第三方平台结果
     * 
     * @param array $result
     * @return Event
     */
    public function setResult(array $result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * 返回Zfirm用户平台结果
     * 
     * @return array
     */
    public function getZfirm()
    {
        return $this->zfirm;
    }

    /**
     * 设置Zfirm用户平台结果
     * 
     * @param array $zfirm
     * @return Event
     */
    public function setZfirm(array $zfirm)
    {
        $this->zfirm = $zfirm;
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