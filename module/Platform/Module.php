<?php
/**
 * 第三方登陆验证
 * 
 * @category MobilePf2
 * @package Platform
 * @namespace Platform
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: Module.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace Platform;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/**
 * Platform模块的管理类
 * 
 * @name Module
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface, 
                        ServiceProviderInterface
{
    /**
     * 
     * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
            		__DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }
    
    /**
     *
     * @see \Zend\ModuleManager\Feature\ConfigProviderInterface::getConfig()
     */
    public function getConfig()
    {
    	return include __DIR__ . '/config/module.config.php';
    }
    
    /**
     *
     * @see \Zend\ModuleManager\Feature\ServiceProviderInterface::getServiceConfig()
     */
    public function getServiceConfig()
    {
    	return array(
    		'abstract_factories' => array('Platform\Log\LoggerAbstractServiceFactory')
    	);
    }
}
