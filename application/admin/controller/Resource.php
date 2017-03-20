<?php
namespace app\admin\controller;

class Resource extends Init{
	
	public function index(){
		input('accept') && $this->assign('accept', input('accept'));
		return $this->fetch();
	}
	
}