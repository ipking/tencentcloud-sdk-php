<?php

namespace TencentCloud\Common\Core;


abstract class Curl{
	
	/**
	 * @return mixed
	 */
	private static function arrayMergeKeepKeys(){
		$arg_list = func_get_args();
		$Zoo = null;
		foreach((array)$arg_list as $arg){
			foreach((array)$arg as $K => $V){
				$Zoo[$K] = $V;
			}
		}
		return $Zoo;
	}
	
	/**
	 * @param $url
	 * @param array $curl_option
	 * @throws HttpException
	 * @return resource
	 */
	private static function getCurlInstance($url, $curl_option = array()){
		if(!$url){
			throw new HttpException('CURL URL NEEDED');
		}
		
		//use ssl
		$ssl = substr($url, 0, 8) == 'https://' ? true : false;
		
		$opt = array(
			CURLOPT_FOLLOWLOCATION => 1,
			CURLOPT_RETURNTRANSFER => true,
		);
		
		if($ssl){
			$opt[CURLOPT_SSL_VERIFYPEER] = 0;
			$opt[CURLOPT_SSL_VERIFYHOST] = 1;
		}
		
		//设置缺省参数
		$curl_option = self::arrayMergeKeepKeys($opt, $curl_option);
		
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		
		$a[CURLOPT_URL] = $url;
		foreach($curl_option as $k => $val){
			$a[$k] = $val;
			curl_setopt($curl, $k, $val);
		}
		return $curl;
	}
	
	
	
	/**
	 * @param string $url
	 * @param array $curl_option
	 * @throws HttpException
	 * @return array
	 */
	public static function execute($url, $curl_option = array()){
		$opt = array(
			CURLOPT_HEADER         => true,
		);
		$curl_option = self::arrayMergeKeepKeys($opt, $curl_option);
		$curl = self::getCurlInstance($url, $curl_option);
		$content = curl_exec($curl);
		
		$curl_errno = curl_errno($curl);
		if($curl_errno>0){
			throw new HttpException($curl_errno);
		}
		
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		$response_headers = substr($content, 0, $header_size);
		// Parse out the headers
		$response_headers = explode("\r\n\r\n", trim($response_headers));
		$response_headers = array_pop($response_headers);
		$response_headers = explode("\r\n", $response_headers);
		array_shift($response_headers);
		// Loop through and split up the headers.
		$header_assoc = array();
		foreach ($response_headers as $header) {
			$kv = explode(': ', $header);
			$header_assoc[strtolower($kv[0])] = isset($kv[1]) ? $kv[1] : '';
		}
		$response_body = substr($content, $header_size);
		curl_close($curl);
		return [$response_body,$response_code,$header_assoc];
	}
}