<?php

namespace TencentCloud\Common\Core;

class Client{
	
	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';
	
	protected static $callback_list;
	
	protected $method;
	
	protected $url;
	
	protected $headers;
	
	protected $data;
	
	protected $client_response;
	
	protected $option;
	
	/**
	 * @param $cb
	 */
	public static function setSendCallback($cb){
		self::$callback_list[] = $cb;
	}
	
	function __construct($option=[])
	{
		$this->option = $option;
	}
	
	/**
	 * @return string
	 */
	public function getMethod(){
		return $this->method;
	}
	
	/**
	 * @return string
	 */
	public function getUrl(){
		return $this->url;
	}
	
	/**
	 * @return array
	 */
	public function getHeaders(){
		return $this->headers;
	}
	
	/**
	 * @return string
	 */
	public function getData(){
		return $this->data;
	}
	
	/**
	 * @return string
	 */
	public function getResponse(){
		return $this->client_response;
	}
	
	
	/**
	 * @param string $uri
	 * @param array $requestOptions
	 * @return array
	 * @throws HttpException
	 */
	public function get($uri, $requestOptions = []){
		
		$this->url = $this->option['base_uri'].$uri;
		$this->method = self::METHOD_GET;
		$this->headers = $requestOptions['headers'];
		
		$header_arr = [];
		foreach($this->headers as $key => $item){
			$header_arr[] = $key.': '.$item;
		}
		$opt = array(
			CURLOPT_HTTPHEADER     => $header_arr,
		);
		$this->client_response = Curl::execute($this->url,$opt);
		
		if(is_array(self::$callback_list)){
			foreach(self::$callback_list as $cb){
				if(is_callable($cb)){
					$cb($this);
				}
			}
		}
		
		list($response_body) = $this->client_response;
		return $response_body;
	}
	
	/**
	 * @param string $uri
	 * @param array $requestOptions
	 * @return array
	 * @throws HttpException
	 */
	public function post($uri, $requestOptions = []){
		
		$this->url = $this->option['base_uri'].$uri;
		$this->method = self::METHOD_POST;
		$this->headers = $requestOptions['headers'];
		if($requestOptions['body']){
			$this->data = $requestOptions['body'];
		}
		
		$header_arr = [];
		foreach($this->headers as $key => $item){
			$header_arr[] = $key.': '.$item;
		}
		
		$opt = array(
			CURLOPT_POST           => true,
			CURLOPT_HTTPHEADER     => $header_arr,
			CURLOPT_POSTFIELDS     => $this->data,
		);
		$this->client_response = Curl::execute($this->url,$opt);
		
		if(is_array(self::$callback_list)){
			foreach(self::$callback_list as $cb){
				if(is_callable($cb)){
					$cb($this);
				}
			}
		}
		
		list($response_body) = $this->client_response;
		return $response_body;
	}
}