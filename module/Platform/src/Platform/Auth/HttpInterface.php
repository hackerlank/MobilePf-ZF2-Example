<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Platform
 * @namespace Platform\Auth
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: HttpInterface.php 40625 2014-01-29 03:41:55Z zhangweiwen $
 */

namespace Platform\Auth;

/**
 * 身份验证的HTTP请求接口
 * 
 * @name HttpInterface
 */
interface HttpInterface
{
    /**
     * 验证身份是否合法
     * 
     * @param array $request 验证身份所需要的参数
     * @return boolean
     */
    public function authenticate(array $request);
}