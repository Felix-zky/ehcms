<?php
namespace app\admin\controller;

class ArticleCategory extends Init{
	
	public function index(){
		return $this->fetch();
	}
	
}