<?php
namespace app\article\controller;

use think\Controller;
use think\Request;
class Index extends Controller{
	use \eh\traits\AsyncFastResponse;
	
	public function index(){
		//db('article')->strict(false)->insert(input('post.'));
// 		print_r($_POST);
// 		print_r(Request::instance()->post());

		return $this->ajaxErrorResult('文章标题不能为空', 'E-030201');
	}
}