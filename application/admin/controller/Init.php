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
use think\Db;

class Init extends Base{
	protected $systemSetting; //系统设置
	protected $personalSetting; //个人设置
	protected $powerIds; //权限分组模块
	protected $powerKeys; //权限分组权限
	protected $noCheckLogin; //不判断登录状态
	protected $classPermission = []; //类权限，用于验证允许权限范围。
	
	public function __construct(){
		parent::__construct();

		//加载语言包，先固定使用zh-cn，后期改多语言。
		Lang::load(APP_PATH . 'admin/lang/zh-cn/error.php');
		Lang::load(APP_PATH . 'admin/lang/zh-cn/success.php');

		//设置布局模板
		$this->view->engine->layout('layout/layout');

		//判断登录状态
		$this->checkUserLogin();

		//获取全局设置
		$result = Db::name('admin_setting')->where('uid', 0)->select();
		
		if ($result){
			foreach ($result as $v){
				$this->systemSetting[$v['key']] = $v['value'];
			}
			$this->assign('systemSetting', $this->systemSetting);
		}

		//获取个人设置
		$result = Db::name('admin_setting')->where('uid', cookie('user_id'))->select();
		
		if ($result){
			foreach ($result as $v){
				$this->personalSetting[$v['key']] = $v['value'];
			}
			$this->assign('personalSetting', $this->personalSetting);
		}

		//获取权限
        $adminGroup = Db::name('admin_group')->alias('g')->field('module_ids, keys')->join('__MEMBER__ m', 'm.admin_group = g.id')->where('m.id', cookie('user_id'))->find();
		$this->powerIds = $adminGroup['module_ids'];
		$this->powerKeys = explode(',', $adminGroup['keys']);
	}
	
	/**
	 * 检查管理员是否登录
	 */
	private function checkUserLogin(){
		$controller = request()->controller();
		$action = request()->action();

		//管理员状态检查及用户状态检查
		if (!session('?eh_admin') || !action('member/User/checkUserLogin', [], 'event')){
		    if (!empty($this->noCheckLogin)){
                if (is_array($this->noCheckLogin)){
                    !in_array($action, $this->noCheckLogin) && $this->redirect(eh_url('U-010301'));
                }else{
                    $action != $this->noCheckLogin && $this->redirect(eh_url('U-010301'));
                }
            }else{
                $controller != 'Login' && $this->redirect(eh_url('U-010301'));
            }
		}elseif ($controller == 'Login'){
            if ($action != 'signOut'){
                $this->redirect(eh_url('U-010201'));
            }
		}
	}

}