<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package QihuTest
 * @namespace QihuTest\Controller
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: UserControllerTest.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace QihuTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * UserController test case.
 * 
 * @name UserControllerTest
 */
class UserControllerTest extends AbstractHttpControllerTestCase
{
    /**
     * Prepares the environment before running a test.
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
    	$this->setApplicationConfig(include APPLICATION_PATH . '/config/application.config.php');
    
    	date_default_timezone_set('Asia/Shanghai');
    
    	parent::setUp();
    }
    
    /**
     * 测试本控制器的路由
     */
    public function testUserControllerRoute()
    {
        $this->dispatch('/360/user.php');
        $this->assertModuleName('Qihu');
        $this->assertControllerName('Qihu\Controller\User');
        $this->assertCOntrollerClass('UserController');
    }
    
    /**
     * 测试默认动作器接受了无效的Get请求后返回500错误
     */
    public function testIndexActionInvalidQueryParamater()
    {
        $this->dispatch('/360/user?action=test');
        $this->assertResponseStatusCode(500);
        
        $match = array('tag' => 'dl', 
                       'child' => array('tag' => 'dt', 'content' => 'Message:'), 
                       'descendant' => array('tag' => 'pre', 'content' => "The query paramater 'test' invalid."));
        $this->assertTag($match, $this->getResponse()->getContent());
    }
    
    /**
     * 测试UserController::VerifyAction()执行失败
     */
    public function testVerifyActionFailure()
    {
        $stub = $this->getMockBuilder('Qihu\Auth\Http')
                     ->setConstructorArgs(array(APP_KEY, APP_SECRET))
                     ->setMethods(array('authenticate'))
                     ->getMock();
        $stub->expects($this->once())->method('authenticate')->with($this->arrayHasKey('code'))->will($this->returnValue(false));
        
        $controller = $this->getApplicationServiceLocator()
                           ->get('ControllerLoader')
                           ->get('Qihu\Controller\User'); /* @var $controller \Qihu\Controller\UserController */
        $controller->setAuthHttpObject($stub);
        
        $this->dispatch('/360/user/verify?code=invalid_code');
        $this->assertJsonStringEqualsJsonString(json_encode(array()), $this->getResponse()->getContent());
    }
    
    /**
     * 测试UserController::VerifyAction()执行成功
     */
    public function testVerifyActionSuccess()
    {
        $stub = $this->getMockBuilder('Qihu\Auth\Http')
                     ->setConstructorArgs(array(APP_KEY, APP_SECRET))
                     ->setMethods(array('authenticate', 'getResponseArray'))
                     ->getMock();
        $stub->expects($this->once())->method('authenticate')->with($this->arrayHasKey('code'))->will($this->returnValue(true));
        $stub->expects($this->once())->method('getResponseArray')->will($this->returnValue(array('zfirm' => 1)));
        
        $controller = $this->getApplicationServiceLocator()
                           ->get('ControllerLoader')
                           ->get('Qihu\Controller\User'); /* @var $controller \Qihu\Controller\UserController */
        $controller->setAuthHttpObject($stub);
        
        $this->dispatch('/360/user/verify?code=valid_code');
        $this->assertArrayHasKey('zfirm', json_decode($this->getResponse()->getContent(), true));
    }
}
