<?php
namespace app\admin\controller;

use app\common\controller\Base;
use think\Lang;

class Init extends Base{
	public function __construct(){
		parent::__construct();

		//加载语言包，先固定使用zh-cn，后期改多语言。
		Lang::load(APP_PATH . 'admin/lang/zh-cn/error.php');
		Lang::load(APP_PATH . 'admin/lang/zh-cn/success.php');
		
		$this->view->engine->layout('layout/layout');
		
		$this->checkUserLogin();
	}
	
	/**
	 * 检查管理员是否登录
	 */
	private function checkUserLogin(){
		$controller = request()->controller();
		
		//管理员状态检查及用户状态检查
		if (!session('?eh_admin') || !action('member/User/checkUserLogin', [], 'event')){
			$controller != 'Login' && $this->redirect(eh_url('U-010301'));
		}elseif ($controller == 'Login'){
			$this->redirect(eh_url('U-010201'));
		}
	}

}