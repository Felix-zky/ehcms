<?php
namespace app\admin\controller;

class Member extends Init{
	public function __construct(){
		parent::__construct();
	}
	
	public function index($page = 1){
		$page = is_numeric($page) ? $page : 1;
		$member = db('member')->field('password', TRUE)->page($page, 15)->select();
		if (!$member){
			
		}
		return $this->fetch();
	}
}