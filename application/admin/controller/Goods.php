<?php
namespace app\admin\controller;

class Goods extends Init{
	
	public function index(){
		$goods = db('goods')->order('id', 'desc')->paginate(20);
		
		$this->assign('goods', $goods);
		return $this->fetch();
	}
	
	public function create(){
		$this->assign('category', $this->getCategory());
		$this->assign('saveUrl', url('save'));
		return $this->fetch();
	}
	
	
	public function getCategory(){
		$parentID = input('parent_id') ?: 0;
		$result = db('goods_category')->where('parent_id', $parentID)->order('id', 'desc')->select();
		if (request()->isAjax()){
			$this->successResult(['category' => $result]);
		}else{
			return $result;
		}
	}
}