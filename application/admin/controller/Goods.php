<?php
// +----------------------------------------------------------------------
// | ehcms [ Efficient Handy Content Management System ]
// +----------------------------------------------------------------------
// | Copyright (c) http://ehcms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zky < zky@ehcms.com >
// +----------------------------------------------------------------------

namespace app\admin\controller;

/**
 * 商品管理
 */
class Goods extends Init{

    /**
     * 商品列表页面
     */
	public function index(){
		$goods = db('goods')->order('id', 'desc')->where('status', 1)->paginate(24);
		
		$this->assign('goods', $goods);
		return $this->fetch();
	}

    /**
     * 商品创建页面
     */
	public function create(){
		$this->assign('category', $this->getCategory());
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}

    /**
     * 商品编辑页面
     * @param int $id 商品编号
     */
	public function edit($id){
		$goods = db('goods')->where('id', $id)->find();
		$categoryID = $goods['category_id'];
		$categoryParentID = 0;
		
		if ($categoryID > 0){
			$category = db('goods_category')->where('id', $categoryID)->find();
			$categoryParentID = $category['parent_id'];
			if ($categoryParentID != 0){
				$categoryChildID = $categoryID;
				$categoryChilds = db('goods_category')->where('parent_id', $categoryParentID)->select();
				$this->assign('categoryChilds', $categoryChilds);
				$this->assign('categoryChildID', $categoryChildID);
			}else{
				$categoryParentID = $categoryID;
			}
		}
		
		if (!empty($goods['images'])){
			$goods['images'] = explode(',', $goods['images']);
		}
		
		$this->assign('goods', $goods);
		$this->assign('categoryParentID', $categoryParentID);
		$this->assign('category', $this->getCategory());
		$this->assign('goodsID', $id);
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}

    /**
     * 商品更新
     * @param int $id 商品编号
     */
	public function update($id){
		$data = input('param.');
		$data['sale_mode'] = !empty($data['sale_mode']) ? 1 : 2;
		$data['images'] = implode(',', $data['images']);
		
		if (request()->isPut()){
			if (db('goods')->where('id', $id)->update($data) == 1){
				$this->successResult('商品更新成功', ['redirect_url' => url('admin/Goods/index')]);
			}else{
				$this->errorResult('商品更新失败');
			}
		}else{
			$this->errorResult('E-03002');
		}
	}

    /**
     * 商品删除
     * @param int $id 商品编号
     */
	public function delete($id){
		$result = db('goods')->where('id', $id)->update([
			'status' => 2
		]);
		
		if ($result == 1){
			$this->successResult('删除成功');
		}else{
			$this->errorResult('删除失败');
		}
	}
	
	/**
	 * 保存（新增入库）商品
	 */
	public function save(){
		$data = input('param.');
		$data['sale_mode'] = !empty($data['sale_mode']) ? 1 : 2;
		$data['images'] = implode(',', $data['images']);
		
		if (request()->isPost()){
			if (db('goods')->insert(array_merge($data, ['uid' => cookie('user_id'), 'create_time' => THINK_START_TIME])) == 1){
				$this->successResult('商品发布成功');
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

    /**
     * 获取商品分类
     * @param int $parentID 父分类编号
     */
	public function getCategory($parentID = 0){
		$parentID = input('parent_id') ?: $parentID;
		$result = db('goods_category')->where('parent_id', $parentID)->order('id', 'desc')->select();
		if (request()->isAjax()){
			$this->successResult(['category' => $result]);
		}else{
			return $result;
		}
	}
}