<?php
namespace app\admin\controller;

class Login extends Init{
	use \eh\traits\Password;
	
	public function __construct(){
		$this->noCheckUserLogin = 1;
		parent::__construct();
	
		$this->view->engine->layout(FALSE);
	}
	
	/**
	 * 管理员登录
	 */
	public function index(){
		return $this->fetch();
	}
	
	/**
	 * 登录验证及跳转
	 */
	public function checkUser(){
		//必须使用post提交
		if (request()->isPost()){
			$redirectUrl = cookie('redirectUrl') ?: url('index');
			$post = input('post.');
			
			//未填写用户名或密码
			if (!$post['username'] || !$post['password']){
				return $this->ajaxErrorResult(lang('E-020101'), 'E-020101');
			}
			
			$member = db('member')->where(['username', $post['username']])->find();
			
			//根据用户查询，无法查找到用户
			if (!$member || !is_array($member)){
				return $this->ajaxErrorResult(lang('E-020103'), 'E-020103');
			}
			
			//密码验证不通过
			if (!$this->checkPassword($post['password'], $member['password'])){
				return $this->ajaxErrorResult(lang('E-020104'), 'E-020104');
			}
			
			session('eh_admin', 1);
			cookie('user_id', $member['id']);
			cookie('user_sign', md5($member['id'].$member['username'].$member['password'].'eh'.$member['add_time']));
			
			$this->redirect($redirectUrl);
		}else{
			return $this->ajaxErrorResult(lang('E-020102'), 'E-020102');
		}
	}
}