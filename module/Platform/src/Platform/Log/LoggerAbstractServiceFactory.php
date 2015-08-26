<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Platform
 * @namespace Platform\Log
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: LoggerAbstractServiceFactory.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace Platform\Log;

use Zend\Log\LoggerAbstractServiceFactory as ZendLoggerAbstractServiceFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * 产生日志对象的抽象工厂
 * 
 * @name LoggerAbstractServiceFactory
 */
class LoggerAbstractServiceFactory extends ZendLoggerAbstractServiceFactory
{
    /**
     * 日志文件名的模式匹配
     * 
     * 在配置文件中可以定义日志文件的文件名格式，当格式中有模式字符串时，该模式字符串将会被替换。
     * 例如：%pattern%被替换为2014-02-19
     * 
     * @var string
     */
    protected $pattern = '%pattern%';
    
    /**
     * 总是从服务管理器中取配置信息
     * 
     * @staticvar boolean
     */
    static protected $alwaysUseServiceConfig = false;
    
    /**
     * 处理Log配置信息中的单元测试开关以及stream相关配置
     * 
     * @param array $config
     * @param ServiceLocatorInterface $services
     * @return void
     */
    protected function processConfig(&$config, ServiceLocatorInterface $services) {
        parent::processConfig($config, $services);
        
        if (!isset($config['writers'])) {
        	return;
        }
        
        foreach ($config['writers'] as $index => $writerConfig) {
            if (isset($writerConfig['name']) && strtolower($writerConfig['name']) == 'stream'
                && isset($writerConfig['options']) && isset($writerConfig['options']['stream'])) {
                $stream = $writerConfig['options']['stream'];
                if (false !== strstr($stream, $this->pattern)) {
                    $config['writers'][$index]['options']['stream'] = str_replace($this->pattern, date('Ymd'), $stream);
                }
            }
        }
    }
    
    /**
     * 返回配置信息，如果开关打开，则清空配置信息以重新从ServiceLocator中取得配置信息。
     * 
     * @param  ServiceLocatorInterface $services
     * @return array
     */
    protected function getConfig(ServiceLocatorInterface $services) {
        if (self::$alwaysUseServiceConfig && null !== $this->config) {
            $this->config = null;
        }
        parent::getConfig($services);
        
        return $this->config;
    }
    
    /**
     * 设置开关
     * 
     * @static
     * @param boolean $flag
     * @return void
     */
    static public function setAlwaysServiceConfig($flag) {
        self::$alwaysUseServiceConfig = (boolean) $flag;
    }
}