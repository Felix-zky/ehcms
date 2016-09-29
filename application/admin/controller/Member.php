<?php
namespace app\admin\controller;

class Member extends Init{
	public function __construct(){
		parent::__construct();
	}
	
	public function index($page = NULL){
		
		if ($page != NULL){
			$page = is_numeric($page) ? $page : 1;
			
			$member = db('member')->field('password', TRUE)->page($page, 15)->select();
			
			if ($member){
				$result = $this->successResult(['member'=>$member], FALSE);
			}else {
				$result = $this->errorResult('E-020201', FALSE);
			}
		}
		
		if (!request()->isAjax()){
			$pages = ceil(db('member')->count());
			
			if ($pages > 0){
				$this->assign('pages', $pages);
			}
		}
		
		return !empty($result) ? $result : $this->fetch();
	}
}