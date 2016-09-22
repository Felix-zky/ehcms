<?php
use eh\EhUrl;

if (!function_exists('eh_url')) {
	
	function eh_url(){
		EhUrl::get();
	}
	
}