<?php
/**
 * 第三方登陆验证
 * 
 * @category MobilePf2
 * @package Zfirm
 * @namespace Zfirm
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: Module.php 40848 2014-02-20 12:58:39Z zhangweiwen $
 */

namespace Zfirm;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/**
 * Zfirm模块的管理类
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
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
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
			'factories' => array(
				'Zfirm\PfServer\User' => 'Zfirm\PfServer\UserServiceFactory'
			),
    	    'aliases' => array(
    	        'ZfirmUser' => 'Zfirm\PfServer\User'
    	    ), 
    	);
    }
}
