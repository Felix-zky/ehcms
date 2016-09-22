<?php
namespace eh;

class EhUrl{
	
	private static $eh_url = [];
	
	public static function load($file){
		$url = include $file;
		self::$eh_url = self::$eh_url + $url;
	}
	
	public static function get($key, $vars = '', $suffix = true, $domain = false){
		return !empty($this->_url[$key]) ? url($this->_url[$key], $vars, $suffix, $domain) : $key;
	}
	
}