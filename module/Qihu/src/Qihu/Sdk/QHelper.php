<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Qihu
 * @namespace Qihu\Sdk
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: QHelper.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

/***************************************************************************
 *
* Copyright (c) 2011 QIHOO360, Inc. All Rights Reserved
*
**************************************************************************/

namespace Qihu\Sdk;

/**
 * 奇虎(360)提供的SDK
 * 
 * @name QHelper
 */
class QHelper
{

	public function getSignature($params, $appSecret, $isKsort=false)
	{
		if($isKsort) {
			ksort($params);
		}

		$sigStr = '';
		foreach($params as $value) {
			$sigStr .= $value . '#';
		}
		$sigStr .= $appSecret;

		return md5($sigStr);
	}
}