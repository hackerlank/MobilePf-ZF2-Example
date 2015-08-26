<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Qihu
 * @namespace Qihu\Auth
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: HttpInterface.php 40625 2014-01-29 03:41:55Z zhangweiwen $
 */

namespace Qihu\Auth;

use Platform\Auth\HttpInterface as PlatformHttpInterface;

/**
 * 奇虎(360)的身份验证的HTTP请求接口
 * 
 * @name HttpInterface
 */
interface HttpInterface extends PlatformHttpInterface
{
    /**
     * 获取Access Token
     * 
     * @param string $code
     * @return array
     */
    public function getAccessToken($code);
    
    /**
     * 获取用户信息
     * 
     * @param string $accessToken
     * @return array
     */
    public function getUserInfo($accessToken);
    
    /**
     * 刷新Access Token
     * 
     * @param string $refreshToken
     * @return array
     */
    public function refreshAccessToken($refreshToken);
    
    /**
     * 查询Access Token信息
     * 
     * @param string $accessToken
     * @return array
     */
    public function getAccessTokenInfo($accessToken);
}