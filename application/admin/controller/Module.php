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

class Module extends Init{
	public function index(){
        $module = db('admin_module')->order('id', 'desc')->paginate(10);
		
		$this->assign('module', $module);
		return $this->fetch();
	}
	
	public function create(){
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}
	
	public function save(){
		$data = input('param.');
		if (db('admin_module')->insert($data) == 1){
			$this->successResult();
		}else{
			$this->errorResult();
		}
	}
	
	public function edit(){
	
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}
}