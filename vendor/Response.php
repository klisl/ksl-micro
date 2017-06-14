<?php

namespace Vendor;

class Response {
	
	private $headers = array();
	
	public function __construct() {
		$this->headers[] = 'Content-Type: text/html; charset=utf-8';
	}
	
	
	//Добавление заголовков браузеру
	public function addHeader($header) {
		$this->headers[] = $header;
	}
	
	
	//Для создания перенаправлений
	public function redirect($url, $status = 302) {
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url), true, $status);
		exit();
	}
	
	
	public function output($result) {
						
		if ($result) {
				foreach ($this->headers as $header) {
					header($header, true);
				}			
			return $result;
		}
	}
	
}