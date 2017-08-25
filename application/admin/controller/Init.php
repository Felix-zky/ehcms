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

use app\common\controller\Base;
use think\Lang;

class Init extends Base{
	protected $systemSetting;
	protected $personalSetting;
	
	public function __construct(){
		parent::__construct();

		//加载语言包，先固定使用zh-cn，后期改多语言。
		Lang::load(APP_PATH . 'admin/lang/zh-cn/error.php');
		Lang::load(APP_PATH . 'admin/lang/zh-cn/success.php');
		
		$this->view->engine->layout('layout/layout');
		
		$this->checkUserLogin();
		
		$result = db('admin_setting')->where('uid', 0)->select();
		
		if ($result){
			foreach ($result as $v){
				$this->systemSetting[$v['key']] = $v['value'];
			}
			$this->assign('systemSetting', $this->systemSetting);
		}
		
		$result = db('admin_setting')->where('uid', cookie('user_id'))->select();
		
		if ($result){
			foreach ($result as $v){
				$this->personalSetting[$v['key']] = $v['value'];
			}
			$this->assign('personalSetting', $this->personalSetting);
		}
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