<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Platform
 * @namespace Platform\Pay
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: HttpInterface.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

namespace Platform\Pay;

/**
 * 支付的HTTP请求接口
 *
 * @name HttpInterface
 */
interface HttpInterface
{
    /**
     * 支付回调处理函数，由第三方提供数据。
     * 
     * @param array $request 第三方提供的数据
     * @return void 
     */
    public function notify(array $request);
}