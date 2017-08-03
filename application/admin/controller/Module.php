<?php
namespace app\admin\controller;

class Module extends Init{
	public function index(){
		$module = db('admin_module')->select();
		
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