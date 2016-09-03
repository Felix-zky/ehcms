<?php
namespace app\resource\controller;

use think\Controller;

class Base extends Controller{
	use \eh\traits\AsyncFastResponse;
	
	public function __construct(){
		parent::__construct();
	}

}