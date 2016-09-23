<?php
namespace app\article\controller;

use app\common\controller\Base;

class Index extends Base{
	
	public function index(){
		//db('article')->strict(false)->insert(input('post.'));
// 		print_r($_POST);
// 		print_r(Request::instance()->post());
	
		return $this->successResult('错误信息', 'U-010401');
	}
}