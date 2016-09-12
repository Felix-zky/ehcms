<?php
namespace eh\traits;

/**
 * 密码处理
 *
 */
trait Password{
	
	/**
	 * 创建密码
	 */
	private function createPassword(){
		
	}
	
	/**
	 * 检查密码
	 */
	private function checkPassword($password, $hash){
		if (is_array($memberInfo)){
			if (password_verify($password, $memberInfo['password'])){
				//session('user_sign', );
			}
		}
	}
}