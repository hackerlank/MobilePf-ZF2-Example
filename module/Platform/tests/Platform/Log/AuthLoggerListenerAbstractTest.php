<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package PlatformTest
 * @namespace PlatformTest\Log
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: AuthLoggerListenerAbstractTest.php 40866 2014-02-21 10:43:11Z zhangweiwen $
 */

namespace PlatformTest\Log;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Platform\Auth\Event;
use Zend\EventManager\EventManager;

/**
 * AuthLoggerListenerAbstract test case.
 * 
 * @name AuthLoggerListenerAbstractTest
 */
class AuthLoggerListenerAbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $stub;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
    	parent::setUp();
    
    	$this->stub = $this->getMockForAbstractClass('Platform\Log\AuthLoggerListenerAbstract', array(), '', false);
    }
    
    /**
     * 测试能正常注册身份验证过程中的事件监听器。
     */
    public function testEventManagerHasDefaultListeners()
    {
    	$eventManager = new EventManager();
    	$eventManager->attach($this->stub);
    	
    	$this->assertFalse($eventManager->getListeners(Event::EVENT_VERIFY_POST)->isEmpty());
    	$this->assertFalse($eventManager->getListeners(Event::EVENT_TOKEN_POST)->isEmpty());
    	$this->assertFalse($eventManager->getListeners(Event::EVENT_FAILURE)->isEmpty());
    }
}
