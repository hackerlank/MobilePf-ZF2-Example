<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package W91
 * @namespace W91\Auth
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: HttpInterface.php 40626 2014-01-29 08:43:17Z zhangweiwen $
 */

namespace W91\Auth;

use Platform\Auth\HttpInterface as PlatformHttpInterface;

/**
 * 91无线的身份验证的HTTP请求接口
 * 
 * @name HttpInterface
 */
interface HttpInterface extends PlatformHttpInterface
{
    /**
     * 检查用户登录的SessionId是否有效
     * 
     * @param string $uin
     * @param string $sessionId
     * @return array
     */
    public function checkSessionId($uin, $sessionId);
}