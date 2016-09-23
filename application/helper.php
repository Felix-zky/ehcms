<?php
use eh\EhUrl;

if (!function_exists('eh_url')) {
	
	function eh_url($key, $vars = '', $suffix = true, $domain = false){
		
		return EhUrl::get($key, $vars, $suffix, $domain);
		
	}
	
}