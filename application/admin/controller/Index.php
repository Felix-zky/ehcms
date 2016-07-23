<?php
namespace app\admin\controller;

class Index extends Base{
	public function __construct(){
		parent::__construct();
		
		$this->view->engine->layout(FALSE);
	}
	
	public function index(){
		return $this->fetch();
	}
	
	public function test(){
		return $this->fetch();
	}
	
}

?>