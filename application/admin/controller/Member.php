<?php
namespace app\admin\controller;

class Member extends Init{
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$member = db('member')->field('id,username,is_admin,create_time')->order('id', 'desc')->paginate(20);
		
		$this->assign('member', $member);
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