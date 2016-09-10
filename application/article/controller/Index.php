<?php
namespace app\article\controller;

class Index{
	public function index(){
		print_r(input('post.'));
	}
}