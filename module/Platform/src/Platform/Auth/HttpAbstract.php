<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Platform
 * @namespace Platform\Auth
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: HttpAbstract.php 41411 2014-04-24 10:56:09Z zhangweiwen $
 */

namespace Platform\Auth;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Stdlib\Parameters;
use Platform\Response\Failure;
use Zfirm\PfServer\User;

/**
 * 身份验证抽象类
 * 
 * @abstract
 * @name HttpAbstract
 */
abstract class HttpAbstract implements HttpInterface, EventManagerAwareInterface, ServiceLocatorAwareInterface
{
    /**
     * 注入ServiceLocatorAwareTrait
     * 
     * @uses \Zend\ServiceManager\ServiceLocatorAwareTrait
     */
    use ServiceLocatorAwareTrait;
    
    /**
     * 注入EventManagerAwareTrait
     * 
     * @uses \Zend\EventManager\EventManagerAwareTrait;
     */
    use EventManagerAwareTrait;
    
    /**
     * 身份验证事件对象
     *
     * @var AuthEvent
     */
    protected $event;
    
    /**
     * Zfirm用户平台的User服务
     * 
     * @var User
     */
    protected $userService;
    
    /**
     * 验证成功的响应结果
     *
     * @var Parameters
     */
    protected $response;
    
    /**
     * 执行第三方的身份数据验证
     * 
     * @abstract
     * @param Event $e
     * @retun mixed
     */
    abstract public function onVerify(Event $e);
    
    /**
     * 执行Zfirm用户平台的身份验证
     * 
     * @abstract
     * @param Event $e
     * @return mixed
     */
    abstract public function onToken(Event $e);
    
    /**
     * 对第三方的账号进行验证，并获取Zfirm用户平台的Token。
     *
     * @see HttpInterface::authenticate()
     */
    public function authenticate(array $request) {
    	$events = $this->getEventManager();
    	$e = $this->getEvent();
    	$e->setTarget($this)->setQuery($request);
    
    	// 开始第三方的账号信息验证
    	$responses = $events->trigger(Event::EVENT_VERIFY, $e, function ($result) {
    		return $result instanceof Failure;
    	});
    	if ($responses->stopped()) {
    		if (($failure = $responses->last()) instanceof Failure) {
    			$e->setFailure($failure);
    			$events->trigger(Event::EVENT_FAILURE, $e);
    		}
    		return false;
    	} else {
    		$this->getResponse()->set('result', $e->getResult() ?: $responses->last());
    	}
    	$events->trigger(Event::EVENT_VERIFY_POST, $e);
    
    	// 开始Zfirm用户平台的账号信息验证
    	$responses = $events->trigger(Event::EVENT_TOKEN, $e, function ($result) {
    		return $result instanceof Failure;
    	});
    	if ($responses->stopped()) {
    		if (($failure = $responses->last()) instanceof Failure) {
    			$e->setFailure($failure);
    			$events->trigger(Event::EVENT_FAILURE, $e);
    		}
    		return false;
    	} else {
    		$this->getResponse()->set('zfirm', $e->getZfirm() ?: $responses->last());
    	}
    	$events->trigger(Event::EVENT_TOKEN_POST, $e);
    
    	return true;
    }
    
    /**
     * 设置身份验证事件，如果提供的参数是其他类型事件，将把事件类型转换。
     *
     * @param EventInterface $event
     * @return HttpAbstract
     */
    public function setEvent(EventInterface $event) {
    	if (!$event instanceof Event) {
    		$event = (new Event())->setParams($event->getParams());
    	}
    	$this->event = $event;
    	
    	return $this;
    }
    
    /**
     * 返回身份验证事件，如果不存在，则创建一个新的事件。
     *
     * @return Event
     */
    public function getEvent() {
    	if (!$this->event) {
    		$this->setEvent(new Event());
    	}
    
    	return $this->event;
    }
    
    /**
     * 设置Zfirm用户平台的User服务
     * 
     * @param User $userService
     * @return HttpAbstract
     */
    public function setUserService(User $userService) {
        $this->userService = $userService;
        
        return $this;
    }
    
    /**
     * 返回Zfirm用户平台的User服务
     * 
     * @return User
     */
    public function getUserService() {
        if (null == $this->userService) {
            $this->userService = $this->serviceLocator->get('ZfirmUser');
        }
        
        return $this->userService;
    }
    
    /**
     * 返回成功的响应结果
     *
     * @return Parameters
     */
    public function getResponse() {
    	if (null === $this->response) {
    		$this->response = new Parameters();
    	}
    
    	return $this->response;
    }
    
    /**
     * 以数组形式返回成功的响应结果
     * 
     * @return array
     */
    public function getResponseArray() {
        return $this->getResponse()->toArray();
    }
    
    /**
     * 注册默认的事件监听器
     *
     * @return void
     */
    protected function attachDefaultListeners() {
    	$events = $this->getEventManager();
    	$events->attach(Event::EVENT_VERIFY, array($this, 'onVerify'));
    	$events->attach(Event::EVENT_TOKEN, array($this, 'onToken'));
    }
}