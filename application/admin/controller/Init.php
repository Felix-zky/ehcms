<?php
namespace app\admin\controller;

use app\common\controller\Base;

class Init extends Base{
	use \eh\traits\AsyncFastResponse;
	
	public function __construct(){
		parent::__construct();
		
		$this->view->engine->layout('admin@layout/layout');
	}
	
	private function checkUserLogin(){
		session();
	}

}