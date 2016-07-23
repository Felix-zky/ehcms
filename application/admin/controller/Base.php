<?php
namespace app\admin\controller;

use think\Controller;

class Base extends Controller{
	
	public function __construct(){
		parent::__construct();
		
		$this->view->engine->layout('admin@layout/layout');
	}
	
}