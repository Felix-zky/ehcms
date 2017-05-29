<?php
namespace app\admin\controller;

class Setting extends Init{
	
	public function system(){
		return $this->fetch();
	}
	
	public function personal(){
		return $this->fetch();
	}
}