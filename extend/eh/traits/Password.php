<?php
namespace eh\traits;

/**
 * 密码处理
 */
trait Password{
	
	/**
	 * 创建密码
	 */
	private function createPassword($password){
		return password_hash($password, PASSWORD_DEFAULT);
	}
	
	/**
	 * 检查密码
	 */
	private function checkPassword($password, $hash){
		return password_verify($password, $hash);
	}
}