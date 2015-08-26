<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package W91
 * @namespace W91\Sdk
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: Sdk.php 40626 2014-01-29 08:43:17Z zhangweiwen $
 */

/**
 * PHP SDK for  OpenAPI
 *
 * @version 1.0
 * @author dev.91.com
 */

namespace W91\Sdk;

/**
 * 91无线提供的SDK
 * 
 * @name Sdk
 */
class Sdk{
	
	/*
	 * 这里的AppId和AppKey是我们自己做测试的
	 * 开发者可以自己根据自己在dev.91.com平台上创建的具体应用信息进行修改
	 */
	private $AppId  = 100010;
	private $AppKey = 'C28454605B9312157C2F76F27A9BCA2349434E546A6E9C75';
	
	
	private $Url = "http://service.sj.91.com/usercenter/ap.aspx";

	public function __construct($appid, $appkey){
		$this->AppId = $appid;
		$this->AppKey = $appkey;
	}

	/**
	 * 执行查询支付购买结果的API调用，返回结果数组
	 *
	 * @param string $CooOrderSerial 商户订单号
	 * @return array 结果数组
	 */
	public function query_pay_result($CooOrderSerial){
		
		$Act = 1;
		//生成Sign
		$Sign = md5($this->AppId.$Act.$CooOrderSerial.$this->AppKey);
		//把需要传送的参数拼接成字符串
		$SourceStr = "AppId=".$this->AppId."&Act=".$Act."&CooOrderSerial=".$CooOrderSerial."&Sign=".$Sign;
		$Params = trim($SourceStr);

		// 发起请求
		$Res = $this->request($this->Url, $Params, 'get');

		if (false === $Res['result']){
			$ResultArray = array(
				'res' => $Res['errno'],
				'msg' => $Res['msg'],
			);
		}

		$ResultArray = json_decode($Res['msg'], true);

		// 远程返回的不是 json 格式, 说明返回包有问题
		if (is_null($ResultArray)){
			$ResultArray = array(
				'res' => false,
				'msg' => $Res['msg']
			);
		}
		return $ResultArray;
	}

	/**
	 * 检查用户登陆SessionId是否有效API调用，返回结果数组
	 *
	 * @param string $Uin 用户的91Uin
	 * @param string $SessionId 用户登陆SessionId
	 * @return array 结果数组
	 */
	public function check_user_login($Uin,$SessionId){

		$Act = 4;
		//生成Sign
		$Sign = md5($this->AppId.$Act.$Uin.$SessionId.$this->AppKey);
		//把需要传送的参数拼接成字符串
		$SourceStr = "AppId=".$this->AppId."&Act=".$Act."&Uin=".$Uin."&SessionId=".$SessionId."&Sign=".$Sign;
		$Params = trim($SourceStr);

		// 发起请求
		$Res = $this->request($this->Url, $Params, 'get');

		if (false === $Res['result']){
			$ResultArray = array(
				'res' => $Res['errno'],
				'msg' => $Res['msg'],
			);
		}

		$ResultArray = json_decode($Res['msg'], true);

		// 远程返回的不是 json 格式, 说明返回包有问题
		if (is_null($ResultArray)) {
			$ResultArray = array(
				'res' => false,
				'msg' => $Res['msg']
			);
		}
		return $ResultArray;
	}
	
	/**
	 * 查询支付结果API调用，返回结果数组
	 *
	 * @param string $CooOrderSerial 商户订单号
	 * @return array 结果数组
	 */
	public function queryorder($CooOrderSerial){

		$Act = 1;
		//生成Sign
		$Sign = md5($this->AppId.$Act.$CooOrderSerial.$this->AppKey);
		//把需要传送的参数拼接成字符串
		$SourceStr = "AppId=".$this->AppId."&Act=".$Act."&CooOrderSerial=".$CooOrderSerial."&Sign=".$Sign;
		$Params = trim($SourceStr);

		// 发起请求
		$Res = $this->request($this->Url, $Params, 'get');

		if (false === $Res['result']){
			$ResultArray = array(
				'res' => $Res['errno'],
				'msg' => $Res['msg'],
			);
		}

		$ResultArray = json_decode($Res['msg'], true);

		// 远程返回的不是 json 格式, 说明返回包有问题
		if (is_null($ResultArray)) {
			$ResultArray = array(
				'res' => false,
				'msg' => $Res['msg']
			);
		}
		return $ResultArray;
	}
	
	/**
	 * 发送用户动态API调用，返回结果数组
	 *
	 * @param string $Uin 用户的91Uin
	 * @param string $TemplateId 模板ID
	 * @param string $ParamList 模板参数列表
	 * @return array 结果数组
	 */
	public function sendmsg($Uin,$TemplateId,$ParamList){

		$Act = 2;
		//生成Sign
		$Sign = md5($this->AppId.$Act.$Uin.$TemplateId.$ParamList.$this->AppKey);
		//把需要传送的参数拼接成字符串
		$SourceStr = "AppId=".$this->AppId."&Act=".$Act."&Uin=".$Uin."&TemplateId=".$TemplateId."&ParamList=".urlencode($ParamList)."&Sign=".$Sign;
		$Params = trim($SourceStr);

		// 发起请求
		$Res = $this->request($this->Url, $Params, 'get');

		if (false === $Res['result']){
			$ResultArray = array(
				'res' => $Res['errno'],
				'msg' => $Res['msg'],
			);
		}

		$ResultArray = json_decode($Res['msg'], true);

		// 远程返回的不是 json 格式, 说明返回包有问题
		if (is_null($ResultArray)) {
			$ResultArray = array(
				'res' => false,
				'msg' => $Res['msg']
			);
		}
		return $ResultArray;
	}
	
	/**
	 * 获取应用的用户列表API调用，返回结果数组
	 *
	 * @param string $PageNo 第几页
	 * @param string $PageSize 每页数量
	 * @return array 结果数组
	 */
	public function userlist($PageNo,$PageSize){

		$Act = 3;
		//生成Sign
		$Sign = md5($this->AppId.$Act.$PageNo.$PageSize.$this->AppKey);
		//把需要传送的参数拼接成字符串
		$SourceStr = "AppId=".$this->AppId."&Act=".$Act."&PageNo=".$PageNo."&PageSize=".$PageSize."&Sign=".$Sign;
		$Params = trim($SourceStr);

		// 发起请求
		$Res = $this->request($this->Url, $Params, 'get');

		if (false === $Res['result']){
			$ResultArray = array(
				'res' => $Res['errno'],
				'msg' => $Res['msg'],
			);
		}

		$ResultArray = json_decode($Res['msg'], true);

		// 远程返回的不是 json 格式, 说明返回包有问题
		if (is_null($ResultArray)) {
			$ResultArray = array(
				'res' => false,
				'msg' => $Res['msg']
			);
		}
		return $ResultArray;
	}
	
	/**
	 * 获取用户当前应用的好友列表API调用，返回结果数组
	 *
	 * @param string $Uin 用户的91Uin
	 * @param string $SessionId 用户登陆SessionId
	 * @param string $PageNo 第几页
	 * @param string $PageSize 每页数量
	 * @return array 结果数组
	 */
	public function friendlist($Uin,$SessionId, $PageNo,$PageSize){

		$Act = 6;
		//生成Sign
		$Sign = md5($this->AppId.$Act.$Uin.$SessionId.$PageNo.$PageSize.$this->AppKey);
		//把需要传送的参数拼接成字符串
		$SourceStr = "AppId=".$this->AppId."&Act=".$Act."&Uin=".$Uin."&SessionId=".$SessionId."&PageNo=".$PageNo."&PageSize=".$PageSize."&Sign=".$Sign;
		$Params = trim($SourceStr);

		// 发起请求
		$Res = $this->request($this->Url, $Params, 'get');

		if (false === $Res['result']){
			$ResultArray = array(
				'res' => $Res['errno'],
				'msg' => $Res['msg'],
			);
		}

		$ResultArray = json_decode($Res['msg'], true);

		// 远程返回的不是 json 格式, 说明返回包有问题
		if (is_null($ResultArray)) {
			$ResultArray = array(
				'res' => false,
				'msg' => $Res['msg']
			);
		}
		return $ResultArray;
	}
	
	/**
	 * 获取用户的好友列表API调用，返回结果数组
	 *
	 * @param string $Uin 用户的91Uin
	 * @param string $SessionId 用户登陆SessionId
	 * @param string $PageNo 第几页
	 * @param string $PageSize 每页数量
	 * @return array 结果数组
	 */
	public function myfriend($Uin,$SessionId, $PageNo,$PageSize){

		$Act = 5;
		//生成Sign
		$Sign = md5($this->AppId.$Act.$Uin.$SessionId.$PageNo.$PageSize.$this->AppKey);
		//把需要传送的参数拼接成字符串
		$SourceStr = "AppId=".$this->AppId."&Act=".$Act."&Uin=".$Uin."&SessionId=".$SessionId."&PageNo=".$PageNo."&PageSize=".$PageSize."&Sign=".$Sign;
		$Params = trim($SourceStr);

		// 发起请求
		$Res = $this->request($this->Url, $Params, 'get');

		if (false === $Res['result']){
			$ResultArray = array(
				'res' => $Res['errno'],
				'msg' => $Res['msg'],
			);
		}

		$ResultArray = json_decode($Res['msg'], true);

		// 远程返回的不是 json 格式, 说明返回包有问题
		if (is_null($ResultArray)) {
			$ResultArray = array(
				'res' => false,
				'msg' => $Res['msg']
			);
		}
		return $ResultArray;
	}

	/**
	 * 执行一个 HTTP 请求
	 *
	 * @param string 	$Url 	执行请求的Url
	 * @param mixed	$Params 表单参数
	 * @param string	$Method 请求方法 post / get
	 * @return array 结果数组
	 */
	public function request($Url, $Params, $Method='post'){

		$Curl = curl_init();//初始化curl
		if ('get' == $Method){//以GET方式发送请求
			curl_setopt($Curl, CURLOPT_URL, "$Url?$Params");
		}else{//以POST方式发送请求
			curl_setopt($Curl, CURLOPT_URL, $Url);
			curl_setopt($Curl, CURLOPT_POST, 1);//post提交方式
			curl_setopt($Curl, CURLOPT_POSTFIELDS, $Params);//设置传送的参数
		}

		curl_setopt($Curl, CURLOPT_HEADER, false);//设置header
		curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
		curl_setopt($Curl, CURLOPT_CONNECTTIMEOUT, 3);//设置等待时间

		$Res = curl_exec($Curl);//运行curl
		$Err = curl_error($Curl);
		@file_put_contents('/tmp/91_request.log', "url: $Url, method: $Method , params: $Params, result: $Res \n",FILE_APPEND);
		if (false === $Res || !empty($Err)){
			$Errno = curl_errno($Curl);
			$Info = curl_getinfo($Curl);
			curl_close($Curl);

			return array(
	        	'result' => false,
	        	'errno' => $Errno,
	            'msg' => $Err,
	        	'info' => $Info,
			);
		}
		curl_close($Curl);//关闭curl
		return array(
        	'result' => true,
            'msg' => $Res,
		);
		 
	}
}
