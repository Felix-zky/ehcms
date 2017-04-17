<?php
namespace app\admin\controller;

use think\Controller;
class ArticleCategory extends Controller{
	
	public function index(){
		return $this->fetch();
	}
	
}