<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Qihu
 * @namespace QihuTest\Auth
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: HttpTest.php 40848 2014-02-20 12:58:39Z zhangweiwen $
 */

namespace QihuTest\Auth;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Platform\Auth\Event;
use Zend\Http\PhpEnvironment\RemoteAddress;

/**
 * Http test case
 *
 * @name Http
 */
class HttpTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $http;
    
    /**
     * 
     * @var array
     */
    protected $servers = array('192.168.1.1:8080');
    
    /**
     * 
     * @var array
     */
    protected $params = array('key' => '12345678901234');
    
    /**
     * 
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $user;
    
    /**
     * Prepares the environment before running a test.
     * 
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->http = $this->getMockBuilder('Qihu\Auth\Http')
                           ->setConstructorArgs(array(APP_KEY, APP_SECRET))
                           ->setMethods(array('getAccessToken', 'getUserInfo', 'refreshAccessToken', 'getAccessTokenInfo'))
                           ->getMock();
        $this->user = $this->getMockBuilder('Zfirm\PfServer\User')
                           ->setConstructorArgs(array($this->servers, $this->params))
                           ->setMethods(array('setToken'))
                           ->getMock();
        $this->http->setUserService($this->user);
    }
    
    /**
     * 测试奇虎(360)的数据验证——code参数为空
     */
    public function testOnVerifyCodeEmpty()
    {
        $failure = $this->http->onVerify(new Event());
        
        $this->assertInstanceOf('Platform\Response\Failure', $failure);
        $this->assertEquals('code参数不存在。', $failure->getReason());
    }
    
    /**
     * 测试奇虎(360)的数据验证——获取access_token失败
     */
    public function testOnVerifyAccessTokenFailure()
    {
        $this->http->expects($this->once())
                   ->method('getAccessToken')
                   ->with($this->equalTo('invalid_code'))
                   ->will($this->returnValue(array()));
        $e = new Event();
        $e->setQuery(array('code' => 'invalid_code'));
        $failure = $this->http->onVerify($e);
        
        $this->assertInstanceOf('Platform\Response\Failure', $failure);
        $this->assertEquals('获取access token失败。', $failure->getReason());
    }
    
    /**
     * 测试奇虎(360)的数据验证——获取获取用户信息失败
     */
    public function testOnVerifyUserInfoFailure()
    {
        $this->http->expects($this->once())
                   ->method('getAccessToken')
                   ->with($this->equalTo('valid_code'))
                   ->will($this->returnValue(array('access_token' => 'valid_token')));
        $this->http->expects($this->once())
                   ->method('getUserInfo')
                   ->with($this->equalTo('valid_token'))
                   ->will($this->returnValue(array()));
        $e = new Event();
        $e->setQuery(array('code' => 'valid_code'));
        $failure = $this->http->onVerify($e);
        
        $this->assertInstanceOf('Platform\Response\Failure', $failure);
        $this->assertEquals('获取用户信息失败。', $failure->getReason());
    }
    
    /**
     * 测试奇虎(360)的数据验证——成功
     */
    public function testOnVerifySuccess()
    {
        $id = rand();
        
        $this->http->expects($this->once())
                   ->method('getAccessToken')
                   ->with($this->equalTo('valid_code'))
                   ->will($this->returnValue(array('access_token' => 'valid_token')));
        $this->http->expects($this->once())
                   ->method('getUserInfo')
                   ->with($this->equalTo('valid_token'))
                   ->will($this->returnValue(array('id' => $id)));
        $e = new Event();
        $e->setQuery(array('code' => 'valid_code'));
        $result = $this->http->onVerify($e);
        
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals($id, $result['id']);
    }
    
    /**
     * 测试Zfirm用户平台的数据验证——失败
     */
    public function testOnTokenFailure()
    {
        $this->user->expects($this->once())
                   ->method('setToken')
                   ->with($this->equalTo('_qihu_m_valid_id'), $this->equalTo((new RemoteAddress())->getIpAddress()), $this->equalTo('valid_name'))
                   ->will($this->returnValue(false));
        $e = new Event();
        $e->setResult(array('id' => 'valid_id', 'name' => 'valid_name'));
        $failure = $this->http->onToken($e);
        
        $this->assertInstanceOf('Platform\Response\Failure', $failure);
        $this->assertEquals('获取Zfirm用户平台登陆Token失败。', $failure->getReason());
    }
    
    /**
     * 测试Zfirm用户平台的数据验证——成功
     */
    public function testOnTokenSuccess()
    {
        $token = rand();
        
        $this->user->expects($this->once())
                   ->method('setToken')
                   ->with($this->equalTo('_qihu_m_valid_id'), $this->equalTo((new RemoteAddress())->getIpAddress()), $this->equalTo('valid_name'))
                   ->will($this->returnValue(array('token' => $token)));
        $e = new Event();
        $e->setResult(array('id' => 'valid_id', 'name' => 'valid_name'));
        $result = $this->http->onToken($e);
        
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($token, $result['token']);
    }
}