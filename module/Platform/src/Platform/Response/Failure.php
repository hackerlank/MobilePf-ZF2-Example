<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Platform
 * @namespace Platform\Response
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: Failure.php 40625 2014-01-29 03:41:55Z zhangweiwen $
 */

namespace Platform\Response;

/**
 * 响应结果——失败
 * 
 * @name Failure
 */
class Failure
{
    /**
     * 失败原因
     * 
     * @var string
     */
    protected $reason;
    
    /**
     * 响应结果
     * 
     * @var mixed
     */
    protected $result;
    
    /**
     * 初始化失败原因
     * 
     * @param string $reason 失败原因
     * @param mixed $result 响应结果
     */
    public function __construct($reason, $result = null) {
        $this->reason = $reason;
        $this->result = $result;
    }
    
    /**
     * 返回失败原因
     * 
     * @return string
     */
    public function getReason() {
        return $this->reason;
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