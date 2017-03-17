<?php
namespace app\admin\controller;

class Module extends Init{
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		return $this->fetch();
	}
}