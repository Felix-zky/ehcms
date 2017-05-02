<?php
namespace app\admin\controller;

class DocumentCategory extends Init{
	
	public function index(){
		$parentID = input('parent_id') ?: 0;
		$lists = Db('document_category')->where('parent_id', $parentID)->order('id', 'desc')->paginate(10);
		
		if ($parentID > 0){
			$parent = Db('document_category')->field('name')->where('id', $parentID)->find();
			$this->assign('parentName', $parent['name']);
		}
		$this->assign('parentID', $parentID);
		$this->assign('category', $lists);
		return $this->fetch();
	}
	
	public function save(){
		$parentID = input('parent_id') ?: 0;
		$name = input('name');
		
		$id = db('document_category')->insertGetId(['name' => $name, 'parent_id' => $parentID]);
		
		$param = [];
		if (input('is_child') == 1 && $parentID > 0){
			$param['parent_id'] = $parentID;
		}
		
		if ($id > 0){
			$this->successResult('文档分类新增成功！', url('admin/DocumentCategory/index'), $param);
		}
	}
	
	public function edit(){
		return $this->fetch();
	}
	
	public function update(){
		
	}
	
	public function delete(){
		
	}
	
}