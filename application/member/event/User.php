<?php
namespace app\member\event;

class User{
	use \eh\traits\Password;
	
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
	 * 验证用户账号密码并创建用户cookie
	 * 
	 * @return TRUE or 错误信息 
	 */
	public function login(){
		$post = input('post.');
			
		//未填写用户名或密码
		if (!$post['username'] || !$post['password']){
			return $this->errorResult('E-020101');
		}
			
		$member = db('member')->where(['username', $post['username']])->find();
			
		//根据用户查询，无法查找到用户
		if (!$member || !is_array($member)){
			return $this->errorResult('E-020103');
		}
			
		//密码验证不通过
		if (!$this->checkPassword($post['password'], $member['password'])){
			return $this->errorResult('E-020104');
		}
		
		cookie('user_id', $member['id']);
		cookie('user_sign', md5($member['id'].$member['username'].$member['password'].'eh'.$member['add_time']));
		
		return TRUE;
	}
}