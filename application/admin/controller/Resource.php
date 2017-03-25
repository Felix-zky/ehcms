<?php
namespace app\admin\controller;

class Resource extends Init{
	
	public function index(){
		input('iframe') && $this->assign('iframe', 1);
		return $this->fetch();
	}
	
	public function uploader(){
		$this->assign('groupName', input('groupName'));
		$this->assign('groupID', input('groupID'));
		return $this->fetch();
	}
	
	public function addGroup(){
		$parentID = input('parentID');
		$name = input('name');
		
		if (empty($name)){
			$this->errorResult('目录名必须设置');
		}
		
		$data = [
			'uid' => cookie('user_id'),
			'name' => $name,
			'create_time' => THINK_START_TIME
		];
		
		if ($parentID > 0){
			$data['parent_id'] = $parentID;
		}
		
		$id = db('resource_group')->insert($data);
		
		if ($id == 1){
			$this->successResult('目录添加成功', ['id' => $id]);
		}else{
			$this->errorResult('目录添加失败');
		}
	}
}