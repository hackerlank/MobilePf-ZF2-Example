<?php
/**
 * 第三方登陆验证
 *
 * @category MobilePf2
 * @package Qihu
 * @namespace Qihu\Sdk
 * @copyright Copyright (c) 2004 - $Year$ Zfirm Inc.
 * @author $Author: zhangweiwen $
 * @version $Id: QHttp.php 40832 2014-02-19 10:06:46Z zhangweiwen $
 */

/***************************************************************************
 *
* Copyright (c) 2011 QIHOO360, Inc. All Rights Reserved
*
**************************************************************************/

namespace Qihu\Sdk;

/**
 * 奇虎(360)提供的SDK
 * Provides http methods.
 * 
 * @name QHttp
 */
class QHttp
{
	// Respons format.
	private $_format         = 'json';

	// Decode returned json data.
	private $_decodeJson     = TRUE;

	private $_connectTimeOut = 30;

	private $_timeOut        = 30;

	private $_userAgent      = 'QIHOO360 PHPSDK API v0.0.1';

	/**
	 * Make an POST request.
	 *
	 * @param string $url      A request url like "https://example.com".
	 * @param array  $data     An array to make query string like "example1=&example2=" .
	 *
	 * @return API results.
	 */
	public function post($url, $data = array())
	{
		$query = "";

		$query = $this->buildHttpQuery($data);


		$response = $this->makeRequest($url,'POST', $query);
		if ('json' === $this->_format && $this->_decodeJson) {
			return json_decode($response, true);
		}
		return $response;
	}

	/**
	 * Make an GET request.
	 *
	 * @param string $url     A request url like "https://example.com".
	 * @param array  $data     An array to make query string like "example1=&example2=" .
	 *
	 * @return API results.
	 */
	public function get($url, $data = array())
	{
		if (!empty($data)) {
			$url .= "?".$this->buildHttpQuery($data);
		}
		//$response = file_get_contents($url);
		$response = $this->makeRequest($url,'GET');
		if ('json' === $this->_format && $this->_decodeJson) {
			return json_decode($response, true);
		}
	}

	/**
	 * Make an HTTP request.
	 *
	 * @param string $url        A request url like "https://example.com/xx.json?example1=&example2=".
	 * @param string $method     Request method is "GET" or "POST".
	 * @param string $postfields A query string post to $url.
	 * @param bool    $multi.
	 *
	 * @return API results.
	 */
	public function makeRequest($url, $method, $postfields = NULL) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
		if ('POST' === $method) {
			curl_setopt($ch, CURLOPT_POST, 1);
			if (!empty($postfields)) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
			}
		}
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_connectTimeOut);
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeOut);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->_userAgent);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}


	/**
	 * Build HTTP Query.
	 *
	 * @param array $params Name => value array of parameters.
	 *
	 * @return string HTTP query.
	 */
	public function buildHttpQuery(array $params)
	{
		if (empty($params)) {
			return '';
		}

		$keys   = $this->urlencode(array_keys($params));
		$values = $this->urlencode(array_values($params));

		$params = array_combine($keys, $values);

		uksort($params, 'strcmp');

		$pairs = array();
		foreach ($params as $key => $value)
		{
			$pairs[] =  $key . '=' . $value;
		}

		return implode('&', $pairs);
	}

	/**
	 * URL Encode.
	 *
	 * @param mixed $item string or array of items to url encode.
	 *
	 * @return mixed url encoded string or array of strings.
	 */
	public function urlencode($item)
	{
		static $search  = array('%7E');
		static $replace = array('~');

		if (is_array($item)) {
			return array_map(array(&$this, 'urlencode'), $item);
		}

		if (is_scalar($item) === false) {
			return $item;
		}

		return str_replace($search, $replace, rawurlencode($item));
	}

	/**
	 * URL Decode.
	 *
	 * @param mixed $item Item to url decode.
	 *
	 * @return string URL decoded string.
	 */
	public function urldecode($item)
	{
		if (is_array($item)) {
			return array_map(array(&$this, 'urldecode'), $item);
		}

		return urldecode($item);
	}

}