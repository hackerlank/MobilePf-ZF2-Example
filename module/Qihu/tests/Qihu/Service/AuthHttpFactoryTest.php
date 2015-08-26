<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package QihuTest
 * @namespace QihuTest\Service
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: AuthHttpFactoryTest.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace QihuTest\Service;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Qihu\Service\AuthHttpFactory;

/**
 * AuthHttpFactory test case
 * 
 * @name AuthHttpFactoryTest
 */
class AuthHttpFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $sm;
    
    /**
     * 
     * @var AuthHttpFactory
     */
    private $factory;
    
    /**
     * Prepares the environment before running a test.
     * 
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->sm = $this->getMockBuilder('Zend\ServiceManager\ServiceManager')->setMethods(array('get'))->getMock();
        $this->factory = new AuthHttpFactory();
    }
    
    /**
     * 测试360配置信息不存在时，抛出InvalidArgumentException异常。
     * 
     * @expectedException Qihu\Exception\InvalidArgumentException
     * @expectedExceptionMessage 配置信息错误，没有找到奇虎(360)的配置信息。
     */
    public function testMissingConfig360()
    {
        $config = array(
        	'mobilepf' => array(
        	    '360' => null
            )
        );
        $this->sm->expects($this->once())->method('get')->will($this->returnValue($config));
        
        $this->factory->createService($this->sm);
    }
    
    /**
     * 测试360配置信息的app_key不存在时，抛出InvalidArgumentException异常。
     * 
     * @expectedException Qihu\Exception\InvalidArgumentException
     * @expectedExceptionMessage 配置信息错误，没有找到奇虎(360)配置信息中的app_key。
     */
    public function testMissingConfig360AppKey()
    {
        $config = array(
        	'mobilepf' => array(
        	    '360' => array(
        	        'app_key' => null, 
        	        'app_secret' => '12345678901234567890123456789012'
        	    )
            )
        );
        $this->sm->expects($this->once())->method('get')->will($this->returnValue($config));
        
        $this->factory->createService($this->sm);
    }
    
    /**
     * 测试360配置信息的app_secret不存在时，抛出InvalidArgumentException异常。
     *
     * @expectedException Qihu\Exception\InvalidArgumentException
     * @expectedExceptionMessage 配置信息错误，没有找到奇虎(360)配置信息中的app_secret。
     */
    public function testMissingConfig360AppSecret()
    {
    	$config = array(
			'mobilepf' => array(
				'360' => array(
				    'app_key' => '12345678901234567890123456789012',
					'app_secret' => null
				)
			)
    	);
    	$this->sm->expects($this->once())->method('get')->will($this->returnValue($config));
    
    	$this->factory->createService($this->sm);
    }
    
    /**
     * 测试返回的对象是Qihu\Auth\Http类的实例
     */
    public function testQihuAuthHttpObject()
    {
        $config = array(
			'mobilepf' => array(
				'360' => array(
					'app_key' => '12345678901234567890123456789012',
					'app_secret' => '12345678901234567890123456789012'
				)
			)
    	);
        $this->sm->expects($this->once())->method('get')->will($this->returnValue($config));
        
        $this->assertInstanceOf('Qihu\Auth\Http', $this->factory->createService($this->sm));
    }
}