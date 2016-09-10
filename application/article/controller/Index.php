<?php
namespace app\article\controller;

class Index{
	public function index(){
		db('article')->strict(false)->insert(input('post.'));
	}
}