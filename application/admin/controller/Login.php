<?php
// +----------------------------------------------------------------------
// | ehcms [ Efficient Handy Content Management System ]
// +----------------------------------------------------------------------
// | Copyright (c) http://ehcms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zky < zky@ehcms.com >
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Login extends Init{
	
	public function __construct(){
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
			$result = action('member/User/login', ['type' => 'admin'], 'event');
			if ($result !== TRUE){
				return $result;
			}else{
				session('eh_admin', 1);
				return $this->successResult('S-020101', 'U-010201');
			}
		}else{
			return $this->errorResult('E-020102');
		}
	}
}