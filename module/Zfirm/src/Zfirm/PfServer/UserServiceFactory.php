<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Zfirm
 * @namespace Zfirm\PfServer
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: UserServiceFactory.php 40625 2014-01-29 03:41:55Z zhangweiwen $
 */

namespace Zfirm\PfServer;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * 产生User服务的对象
 * 
 * @name UserServiceFactory
 */
class UserServiceFactory implements FactoryInterface
{
    /**
     * 
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->get('Config')['zfirm']['user'];
        
        return new User($config['servers'], $config['params']);
    }
}