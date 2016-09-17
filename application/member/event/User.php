<?php
namespace app\member\event;

class User{
	
	/**
	 * 检查用户登录状态
	 */
	public function checkUserLogin(){
		if (!cookie('?user_id') || !cookie('?user_sign')){
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * 验证用户账号密码
	 */
	public function login(){
		
	}
}