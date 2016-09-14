<?php
namespace app\article\controller;

use think\Controller;
use think\Request;
use think\Lang;
class Index extends Controller{
	use \eh\traits\AsyncFastResponse;
	
	public function index(){
		//db('article')->strict(false)->insert(input('post.'));
// 		print_r($_POST);
// 		print_r(Request::instance()->post());
		
		Lang::load(ROOT_PATH . 'lang/zh-cn/error.php');
		Lang::load(APP_PATH . 'lang/zh-cn/success.php');

		return $this->ajaxErrorResult(Lang::get('E-030201'), 'E-030201');
	}
}