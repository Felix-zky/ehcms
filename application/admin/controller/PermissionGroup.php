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

/**
 * 权限分组
 */
class PermissionGroup extends Init{

    /**
     * 权限分组列表页面
     */
	public function index(){
		$group = model('permission_group')->order('id', 'desc')->paginate(10);
		$this->assign('group', $group);
		return $this->fetch();
	}

    /**
     * 新增权限分组页面
     */
	public function create(){
		$module = db('admin_module')->select();
		$this->assign('module', $module);
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}

    /**
     * 新增权限分组
     */
	public function save(){
		$data = input('param.');
		if (db('admin_permission_group')->insert($data) == 1){
			$this->successResult();
		}else{
			$this->errorResult();
		}
	}
	
}