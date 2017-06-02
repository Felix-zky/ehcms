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
	
	/**
	 * 获取用户列表
	 * @param int $page
	 * @return json
	 */
	public function getMemberList($page = 1){
		$memberModel = new \app\member\model\Member();
		
		$request = $this->asyncPostCheck();
		if ($request !== TRUE){
			return $request;
		}
		
		$page = is_numeric($page) ? $page : 1;
		
 		$member = $memberModel->field('password', TRUE)->page($page, 15)->select();
		
		if ($member){
			return $this->successResult(['member'=>$member]);
		}else {
			return $this->errorResult('E-020201');
		}
	}
}