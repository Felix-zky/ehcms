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

class PermissionGroup extends Init{
	
	public function index(){
		$group = model('permission_group')->paginate(10);
		$this->assign('group', $group);
		return $this->fetch();
	}
	
	public function create(){
		$module = db('admin_module')->select();
		$this->assign('module', $module);
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}
	
	public function save(){
		$data = input('param.');
		if (db('admin_permission_group')->insert($data) == 1){
			$this->successResult();
		}else{
			$this->errorResult();
		}
	}
	
}