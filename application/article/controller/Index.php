<?php
namespace app\article\controller;

use think\Controller;
use think\Request;
class Index extends Controller{
	public function index(){
		//db('article')->strict(false)->insert(input('post.'));
		print_r($_POST);
		print_r(Request::instance()->post());
	}
}