<?php
namespace eh\EhUrl;

class EhUrl{
	private $_url;
	
	public function __construct(){
		$this->_url = include '';
	}
	
	public static function get($key){
		return !empty($this->_url[$key]) ? $this->_url[$key] : $key;
	}
	
}