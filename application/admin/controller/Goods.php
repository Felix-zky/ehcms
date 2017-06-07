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
	
	/**
	 * 保存（新增入库）商品
	 */
	public function save(){
		if (request()->isPost()){
			if (db('goods')->insert(array_merge(input('param.'), ['uid' => cookie('user_id'), 'create_time' => THINK_START_TIME])) == 1){
				$this->successResult('商品发布成功', '/admin/goods');
			}else{
				$this->errorResult('商品发布失败');
			}
		}else{
			$this->errorResult('E-03002');
		}
	}
	
	/**
	 * 上传缩略图及商品组图
	 */
	public function resource(){
		$file = request()->file('file');
	
		if (is_object($file)){
			$resource = new \eh\Resource();
			$result = $resource->uploader($file, (int)input('groupID'));
				
			if ($result > 0){
				$this->successResult('上传成功', ['url' => $resource->getData('url')]);
			}else{
				$this->errorResult($resource->getError());
			}
		}else{
			$this->errorResult('获取上传文件失败');
		}
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