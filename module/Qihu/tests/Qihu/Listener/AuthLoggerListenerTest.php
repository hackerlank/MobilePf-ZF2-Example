<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package QihuTest
 * @namespace QihuTest\Listener
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: AuthLoggerListenerTest.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace QihuTest\Listener;

use PHPUnit_Framework_TestCase;
use Zend\Log\Logger;
use Zend\EventManager\EventManager;
use Qihu\Listener\AuthLoggerListener;
use org\bovigo\vfs\vfsStream;
use Platform\Auth\Event;
use Platform\Response\Failure;

/**
 * AuthLoggerListenerTest test case
 *
 * @name AuthLoggerListenerTest
 */
class AuthLoggerListenerTest extends PHPUnit_Framework_TestCase
{
    /**#@+
     * 
     * 测试目录的名字
     */
    const TEST_LOGPATH = 'Test';
    /**#@-*/
    
    /**
     * 
     * @var EventManager
     */
    protected $events;
    
    /**
     * 
     * @var string
     */
    private $destfile;
    
    /**
     * 
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        vfsStream::setup(self::TEST_LOGPATH);
        $this->destfile = vfsStream::url(self::TEST_LOGPATH . '/test.log');
        
        date_default_timezone_set('Asia/Shanghai');
        
        $logger = new Logger();
        $logger->addWriter('stream', null, array('stream' => $this->destfile));
        $this->events = new EventManager();
        $this->events->attach(new AuthLoggerListener($logger));
    }
    
    /**
     * 测试AuthLoggerListener::onVerifyPost()
     */
    public function testOnVerifyPost()
    {
        $e = new Event();
        $e->setQuery(array('code' => '1234567890'));
        $this->events->trigger(Event::EVENT_VERIFY_POST, $e);
        
        $this->assertStringMatchesFormat('%s INFO (6): 360, code: 1234567890%w', file_get_contents($this->destfile));
    }
    
    /**
     * 测试AuthLoggerListener::onTokenPost()
     */
    public function testOnTokenPost()
    {
        $e = new Event();
        $e->setParam('access_token', 'qihu_token');
        $e->setParam('account', "_qihu_m_test_id");
        $e->setParam('ip', '192.168.0.1');
        $e->setParam('nickname', iconv('utf-8', 'gbk', 'test_name'));
        $e->setResult(array('id' => 'test_id', 'name' => 'test_name', 'avator' => 'test_avator', 'nick' => 'test_nick'));
        $e->setZfirm(array('token' => 'zfirm_token'));
        $this->events->trigger(Event::EVENT_TOKEN_POST, $e);
        
        $this->assertStringMatchesFormat('%s INFO (6): 360, access_token: qihu_token, account: _qihu_m_test_id, ip: 192.168.0.1, token: zfirm_token%w', file_get_contents($this->destfile));
    }
    
    /**
     * 测试AuthLoggerListener::onFailure()
     */
    public function testOnFailure()
    {
        $e = new Event();
        $e->setFailure(new Failure('Test', array('result' => '123456')));
        $this->events->trigger(Event::EVENT_FAILURE, $e);
        
        $this->assertStringMatchesFormat('%s WARN (4): Test {"result":"123456"}%w', file_get_contents($this->destfile));
    }
}