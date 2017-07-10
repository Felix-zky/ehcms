<?php
namespace app\admin\controller;

class SettingSms extends Init{
	
	public function index(){
		if (request()->isPost()){
			
		}else{
			return $this->fetch();
		}
	}
	
}