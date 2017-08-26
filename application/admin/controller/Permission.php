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

class Permission extends Init{
	
	public function index(){
		$permission = db('admin_permission')->select();
		return $this->fetch();
	}
	
	public function create(){
		$module = db('admin_module')->select();
		$this->assign('module', $module);
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}
	
	public function save(){
		
	}
	
	public function getGroup(){
		$mouduleID = input('moudule_id');
		
		$group = db('admin_permission_group')->field('id, name')->where('module_id', $mouduleID)->order('id desc')->select();
		
		if (!$group){
			$this->errorResult();
		}
		
		$this->successResult($group);
	}
}