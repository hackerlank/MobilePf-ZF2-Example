<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Platform
 * @namespace Platform\Response
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: Success.php 40625 2014-01-29 03:41:55Z zhangweiwen $
 */

namespace Platform\Response;

/**
 * 响应结果——成功
 *
 * @name Success
 */
class Success
{
    /**
     * 
     * @var mixed
     */
    protected $result;
    
    /**
     * 初始化响应结果
     * 
     * @param mixed $result
     */
    public function __construct($result) {
        $this->result = $result;
    }
    
    /**
     * 返回响应结果
     * 
     * @return mixed
     */
    public function getResult() {
        return $this->result;
    }
}