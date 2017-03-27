<?php
namespace app\admin\controller;

use app\admin\model\Resource as ResourceModel;
use app\admin\model\ResourceGroup;
class Resource extends Init{
	
	public function index(){
		$result = ResourceGroup::withCount('resource')->where('uid', cookie('user_id'))->select();
		
		$count = ResourceModel::where('uid', cookie('user_id'))->count();
		$notGroupedCount = ResourceModel::where(['uid' => cookie('user_id'), 'group_id' => 0])->count();
		
		foreach ($result as $v){
			if ($v['parent_id'] == 0){
				$group[$v['id']] = [
					'id' => $v['id'],
					'name' => $v['name'],
					'count' => $v['resource_count']
				];
			}else{
				$group[$v['parent_id']]['children'][] = [
					'id' => $v['id'],
					'name' => $v['name'],
					'count' => $v['resource_count']
				];
				$group[$v['parent_id']]['count'] += $v['resource_count'];
			}
		}
		
		$resource = ResourceModel::where('uid', cookie('user_id'))->limit(16)->select();
		
		$this->assign('resource', $resource);
		$this->assign('count', $count);
		$this->assign('notGroupedCount', $notGroupedCount);
		$this->assign('group', $group);
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
		
		$id = db('resource_group')->insertGetId($data);
		
		if ($id > 0){
			$this->successResult('目录添加成功', ['id' => $id]);
		}else{
			$this->errorResult('目录添加失败');
		}
	}
}