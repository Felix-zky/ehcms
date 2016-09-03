<?php
namespace app\admin\controller;

use think\Controller;

class Base extends Controller{
	use \eh\traits\AsyncFastResponse;
	
	public function __construct(){
		parent::__construct();
		
		$this->view->engine->layout('admin@layout/layout');
	}

}