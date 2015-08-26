<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package PlatformTest
 * @namespace PlatformTest\Auth
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: HttpAbstractTest.php 40866 2014-02-21 10:43:11Z zhangweiwen $
 */

namespace PlatformTest\Auth;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Platform\Auth\Event;
use Platform\Response\Failure;
use Zend\Stdlib\Parameters;

/**
 * HttpAbstract test case.
 * 
 * @name HttpAbstractTest
 */
class HttpAbstractTest extends PHPUnit_Framework_TestCase
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
        
        $this->stub = $this->getMockForAbstractClass('Platform\Auth\HttpAbstract');
    }
    
    /**
     * 测试在事件管理器默认注册了身份验证事件的监听器
     */
    public function testEventManagerHasDefaultListeners()
    {
        $listeners = $this->stub->getEventManager()->getListeners(Event::EVENT_VERIFY);
        $this->assertFalse($listeners->isEmpty());
        
        $listeners = $this->stub->getEventManager()->getListeners(Event::EVENT_TOKEN);
        $this->assertFalse($listeners->isEmpty());
    }
    
    /**
     * 测试抽象类HttpAbstract中的抽象方法onVerify()和 onToken()返回正确结果后，authenticate()返回值为true。
     */
    public function testAuthenticateTrue()
    {
        $this->stub->expects($this->once())->method('onVerify')->will($this->returnValue(true));
        $this->stub->expects($this->once())->method('onToken')->will($this->returnValue(true));
        
        $this->assertTrue($this->stub->authenticate(array()));
    }
    
    /**
     * 测试执行authenticate()成功后，调用getResponse()是否返回正常结果。
     */
    public function testAuthenticateSuccessResponse()
    {
        $result = array('test1' => rand());
        $this->stub->expects($this->once())->method('onVerify')->will($this->returnValue($result));
        $zfirm = array('test2' => rand());
        $this->stub->expects($this->once())->method('onToken')->will($this->returnValue($zfirm));
        
        $this->stub->authenticate(array());
        $this->assertInstanceOf('Zend\Stdlib\Parameters', $this->stub->getResponse());
        $this->assertEquals(new Parameters(array('result' => $result, 'zfirm' => $zfirm)), $this->stub->getResponse());
        $this->assertSame(array('result' => $result, 'zfirm' => $zfirm), $this->stub->getResponseArray());
    }
    
    /**
     * 测试抽象类HttpAbstract中的抽象方法onVerify返回错误结果后，authenticate()返回值为false。
     */
    public function testAuthenticateFalseWhileOnVerifyReturnFailure()
    {
        $this->stub->expects($this->once())->method('onVerify')->will($this->returnValue(new Failure('PHPUnit test.')));
        
        $this->assertFalse($this->stub->authenticate(array()));
    }
    
    /**
     * 测试抽象类HttpAbstract中的抽象方法onToken返回错误结果后，authenticate()返回值为false。
     */
    public function testAuthenticateFalseWhileOnTokenReturnFailure()
    {
        $this->stub->expects($this->once())->method('onVerify')->will($this->returnValue(true));
        $this->stub->expects($this->once())->method('onToken')->will($this->returnValue(new Failure('PHPUnit test.')));
        
        $this->assertFalse($this->stub->authenticate(array()));
    }
}