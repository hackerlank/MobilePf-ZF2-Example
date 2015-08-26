<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package PlatformTest
 * @namespace PlatformTest\Log
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: LoggerAbstractServiceFactoryTest.php 40866 2014-02-21 10:43:11Z zhangweiwen $
 */

namespace PlatformTest\Log;

use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use org\bovigo\vfs\vfsStream;

/**
 * LoggerAbstractServiceFactory test case.
 * 
 * @name LoggerAbstractServiceFactoryTest
 */
class LoggerAbstractServiceFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     * @var ServiceManager
     */
    protected $serviceManager;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        date_default_timezone_set('Asia/Shanghai');
        
        vfsStream::setup('Test');
        
        $this->serviceManager = new ServiceManager(new ServiceManagerConfig(array(
        	'abstract_factories' => array('Platform\Log\LoggerAbstractServiceFactory'), 
        )));
        $this->serviceManager->setService('Config', array(
        	'log' => array(
        	    'test-log' => array(
        	        'writers' => array(
        	            array(
            	            'name' => 'stream', 
            	            'options' => array(
            	                'stream' => vfsStream::url("Test/%pattern%.log"), 
            	            ), 
        	            ), 
        	    	), 
        	    ), 
            ), 
        ));
    }
    
    /**
     * 测试Zend\Log的配置信息中含有%pattern%参数会被替换为当前的日期时间字符串
     */
    public function testLogConfigStreamOptionPatternReplaceDatetimeString()
    {
        $logger = $this->serviceManager->get('test-log'); /* @var $logger \Zend\Log\Logger */
        
        $this->assertInstanceOf('Zend\Log\Logger', $logger);
        $this->assertFileExists('vfs://Test/' . date('Ymd') . '.log');
    }
}
