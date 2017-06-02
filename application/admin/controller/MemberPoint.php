<?php
namespace app\admin\controller;

class MemberPoint extends Init{
	
	public function index(){
		return $this->fetch();
	}
	
	public function edit(){
		if (request()->isAjax()){
			
		}
		
		return $this->fetch();
	}
	
	public function bind(){
		if (request()->isAjax()){
			
		}
		
		return $this->fetch();
	}

}