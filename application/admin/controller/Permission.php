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
		$data = db('admin_permission')
				->field('p.id, p.name, p.key, p.is_menu, pg.name as group_name, m.name as module_name')
				->alias('p')
				->join('admin_permission_group pg', 'pg.id = p.group_id')
				->join('admin_module m', 'm.id = pg.module_id')
				->paginate(10);
		
		$this->assign('data', $data);
		return $this->fetch();
	}
	
	public function create(){
		$module = db('admin_module')->select();
		$this->assign('module', $module);
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}
	
	public function save(){
		$post = input('param.');
		
		$data = [
			'group_id' => $post['group_id'],
			'name' => $post['name'],
			'key' => $post['key'],
			'is_menu' => $post['is_menu'] == 'on' ? 1 : 0,
			'menu_icon' => $post['menu_icon'],
			'menu_url' => $post['menu_url'],
		];
		
		if (db('admin_permission')->insert($data) == 1){
			$this->successResult();
		}else{
			$this->errorResult();
		}
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