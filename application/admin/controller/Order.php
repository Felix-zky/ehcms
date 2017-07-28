<?php
namespace app\admin\controller;

class Order extends Init{
	
	public function index(){
		$order = model('order')->order('id', 'desc')->paginate(10);
	
		$this->assign('order', $order);
		return $this->fetch();
	}
	
}