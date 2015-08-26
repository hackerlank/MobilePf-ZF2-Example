<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package W91
 * @namespace W91\Service
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: AuthHttpFactory.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace W91\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use W91\Auth\Http;
use W91\Listener\AuthLoggerListener;
use W91\Exception\InvalidArgumentException;

/**
 * 产生91无线的身份验证对象
 * 
 * @name AuthHttpFactory
 */
class AuthHttpFactory implements FactoryInterface
{
    /**
     * 创建91无线身份验证对象，并注册日志处理监听器。
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return Http;
     * @throws InvalidArgumentException
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->get('Config')['mobilepf']['91'];
        if (!array_key_exists('91', $config) || !is_array($config['91'])) {
        	throw new InvalidArgumentException('配置信息错误，没有找到奇虎(360)的配置信息。');
        }
        $config = $config['91'];
        if (!array_key_exists('app_key', $config) || !is_string($config['app_key'])) {
        	throw new InvalidArgumentException('配置信息错误，没有找到奇虎(360)配置信息中的app_key。');
        }
        if (!array_key_exists('app_secret', $config) || !is_string($config['app_secret'])) {
        	throw new InvalidArgumentException('配置信息错误，没有找到奇虎(360)配置信息中的app_secret。');
        }
        $w91 = new Http($config['app_id'], $config['app_key']);
        if ($serviceLocator->has('auth-log')) {
            $w91->getEventManager()->attach(new AuthLoggerListener($serviceLocator->get('auth-log')));
        }
        
        return $w91;
    }
}