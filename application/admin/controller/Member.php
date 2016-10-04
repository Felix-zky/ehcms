<?php
namespace app\admin\controller;

class Member extends Init{
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$pages = ceil(db('member')->count());
			
		if ($pages > 0){
			$this->successResult(['pages' => $pages]);
		}
		
		return $this->fetch();
	}
	
	public function getMemberList($page = 1){
		echo $page;
// 		if (!request()->isAjax()){
// 			return $this->errorResult('E-010101');
// 		}
		
// 		if (!request()->isPost()){
// 			return $this->errorResult('E-010102');
// 		}
		
// 		$page = is_numeric(input('post.page')) || 1;
	
// 		$member = db('member')->field('password', TRUE)->page($page, 15)->select();
	
// 		if ($member){
// 			return $this->successResult(['member'=>$member]);
// 		}else {
// 			return $this->errorResult('E-020201');
// 		}
		
	}
}