<?php
namespace app\admin\controller;

class Permission extends Init{
	
	public function index(){
		$permission = db('admin_permission')->select();
		return $this->fetch();
	}
	
	public function create(){
		return $this->fetch();
	}
	
	public function save(){
		
	}
	
}