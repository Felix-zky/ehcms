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

class AdminGroup extends Init{
	
	public function index(){
	    $group = db('admin_group')->field('keys', true)->order('id', 'desc')->paginate(20);
	    $this->assign('group', $group);
		return $this->fetch();
	}
	
	public function create(){
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}
	
	public function save(){
	    if (db('admin_group')->insert(input()) == 1){
	        $this->successResult('权限新增成功');
        }else{
	        $this->errorResult('权限新增失败');
        }
	}

	public function edit($id){
        $this->assign('actionSign', 'editor');
	    return $this->fetch('editor');
    }

	public function getPermission(){
		$type = input('type');
		$group = input('group');
		$selected = [];
		
		$module = db('admin_module')->select();
		$permissionResult = db('admin_permission')->field('p.id, p.group_id, p.name, p.key, pg.name as group_name, pg.module_id')->alias('p')->join('admin_permission_group pg', 'pg.id = p.group_id')->select();
			
		$permission = [];
		foreach ($permissionResult as $v){
			if (empty($permission[$v['module_id']][$v['group_id']])){
				$permission[$v['module_id']][$v['group_id']] = [
						'name' => $v['group_name'],
						'permission' => []
				];
			}
		
			$permission[$v['module_id']][$v['group_id']]['permission'][$v['id']] = [
					'name' => $v['name'],
					'key' => $v['key']
			];
		}
		
		if ($type == 'edit' && !emtpy($group)){
			
		}

		$data = [
			'module' => $module,
			'permission' => $permission
		];
		
		$this->successResult($data);
	}
}