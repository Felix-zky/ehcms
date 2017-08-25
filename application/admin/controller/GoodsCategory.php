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

use think\Db;
class GoodsCategory extends Init{
	
	public function index(){
		$parentID = input('parent_id') ?: 0;
		$lists = Db('goods_category')->where('parent_id', $parentID)->order('id', 'desc')->paginate(10);
		
		if ($parentID > 0){
			$parent = Db('goods_category')->field('name')->where('id', $parentID)->find();
			$this->assign('parentName', $parent['name']);
		}
		$this->assign('parentID', $parentID);
		$this->assign('category', $lists);
		return $this->fetch();
	}
	
	public function save(){
		$parentID = input('parent_id') ?: 0;
		$name = input('name');
		
		$id = db('goods_category')->insertGetId(['name' => $name, 'parent_id' => $parentID]);
		
		if ($id > 0){
			$this->success('商品分类新增成功！');
		}
	}
	
	public function update(){
		$id = input('id');
		$name = input('name');
	
		if (db('goods_category')->where('id', $id)->update(['name' => $name]) == 1){
			$this->success('商品分类更新成功！');
		}
	}
	
}