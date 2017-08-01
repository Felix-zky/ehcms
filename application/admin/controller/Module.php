<?php
namespace app\admin\controller;

class Module extends Init{
	public function index(){
		$module = db('admin_module')->select();
		$menus = db('admin_menu')->field('name, module_id')->where('parent_id', 0)->group('module_id')->select();
		
		var_dump($menus);
		die;
		
		$this->assign('module', $module);
		return $this->fetch();
	}
}