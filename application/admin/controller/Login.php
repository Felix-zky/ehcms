<?php
namespace app\admin\controller;

class Login extends Init{
	
	public function __construct(){
		parent::__construct();
	
		$this->view->engine->layout(FALSE);
	}
	
	public function index(){
		return $this->fetch();
	}
}