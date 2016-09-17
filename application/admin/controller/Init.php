<?php
namespace app\admin\controller;

use app\common\controller\Base;

class Init extends Base{
	protected $noCheckUserLogin = 0;
	
	public function __construct(){
		parent::__construct();
		
		$this->view->engine->layout('layout/layout');
		
		//由于后台绝大部分页面需要登陆后操作，所有默认登录检测。
		if ($this->noCheckUserLogin != 1){
			$this->checkUserLogin();
		}
	}
	
	/**
	 * 检查管理员是否登录
	 */
	private function checkUserLogin(){
		//管理员状态检查及用户状态检查
		if (!session('?eh_admin') || action('member/User/checkUserLogin', [], 'event')){
			cookie('redirectUrl', url());
			$this->redirect('Login/index');
		}
	}

}