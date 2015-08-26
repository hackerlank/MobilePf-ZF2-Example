<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Qihu
 * @namespace Qihu\Service
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: AuthHttpFactory.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace Qihu\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Qihu\Auth\Http;
use Qihu\Listener\AuthLoggerListener;
use Qihu\Exception\InvalidArgumentException;

/**
 * 产生奇虎(360)的身份验证对象
 * 
 * @name AuthHttpFactory
 */
class AuthHttpFactory implements FactoryInterface
{
    /**
     * 创建奇虎(360)身份验证对象，并注册日志处理监听器。
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return Http;
     * @throws InvalidArgumentException
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->get('Config')['mobilepf'];
        if (!array_key_exists('360', $config) || !is_array($config['360'])) {
            throw new InvalidArgumentException('配置信息错误，没有找到奇虎(360)的配置信息。');
        } 
        $config = $config['360'];
        if (!array_key_exists('app_key', $config) || !is_string($config['app_key'])) {
            throw new InvalidArgumentException('配置信息错误，没有找到奇虎(360)配置信息中的app_key。');
        }
        if (!array_key_exists('app_secret', $config) || !is_string($config['app_secret'])) {
            throw new InvalidArgumentException('配置信息错误，没有找到奇虎(360)配置信息中的app_secret。');
        }
        $qihu = new Http($config['app_key'], $config['app_secret']);
        if ($serviceLocator->has('auth-log')) {
            $qihu->getEventManager()->attach(new AuthLoggerListener($serviceLocator->get('auth-log')));
        }
        
        return $qihu;
    }
}