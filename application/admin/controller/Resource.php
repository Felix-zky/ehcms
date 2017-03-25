<?php
namespace app\admin\controller;

class Resource extends Init{
	
	public function index(){
		input('iframe') && $this->assign('iframe', 1);
		return $this->fetch();
	}
	
	public function uploader(){
		$this->assign('groupName', input('groupName'));
		$this->assign('groupID', input('groupID'));
		return $this->fetch();
	}
}