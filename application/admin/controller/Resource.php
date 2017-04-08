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
		
		$groupID = input('group');
		if (!isset($groupID) || !is_numeric($groupID)){
			$groupID = 'all';
		}
		$groupWhere = [];
		if (is_numeric($groupID)){
			$groupWhere = [
				'group_id' => $groupID
			];
		}
		
		$resource = ResourceModel::where('uid', cookie('user_id'))->where($groupWhere)->order('id desc')->paginate(16);
		
		$this->assign('groupID', $groupID);
		$this->assign('resource', $resource);
		$this->assign('page', $resource->render());
		$this->assign('count', $count);
		$this->assign('notGroupedCount', $notGroupedCount);
		$this->assign('group', $group);
		input('iframe') && $this->assign('iframe', 1);
		return $this->fetch();
	}
	
	public function uploader(){
		if (request()->isAjax()){
			$file = request()->file('file');
			
			if (is_object($file)){
				$resource = new \eh\Resource();
				$result = $resource->uploader($file, (int)input('groupID'));
				
				if ($result > 0){
					$this->successResult('上传成功', ['resourceID' => $result]);
				}else{
					$this->errorResult($resource->getError());
				}
			}else{
				$this->errorResult('获取上传文件失败');
			}
		}else{
			$parentGroupID = input('parentGroupID');
			$childrenGroupID = input('childrenGroupID');
			
			if ($parentGroupID == 'all' || $parentGroupID == 0){
				$group = [
					'id' => 0,
					'name' => '未分组'
				];
			}elseif (empty($childrenGroupID)){
				$result = db('resource_group')->field('name')->where(['id' => $parentGroupID, 'parent_id' => 0, 'uid' => cookie('user_id')])->find();
				if (!$result || empty($result['name'])){
					$group = [
						'id' => 0,
						'name' => '未分组'
					];
				}else{
					$group = [
						'id' => $parentGroupID,
						'name' => $result['name']
					];
				}
			}else{
				$where = [
					'id' => ['in', [$parentGroupID, $childrenGroupID]],
					'uid' => cookie('user_id')
				];
				$result = db('resource_group')->field('name')->where($where)->order('id asc')->select();
				if (!$result || count($result) != 2){
					$group = [
						'id' => 0,
						'name' => '未分组'
					];
				}else{
					foreach ($result as $v){
						$names[] = $v['name'];
					}
			
					$group = [
						'id' => $childrenGroupID,
						'name' => join(' > ', $names)
					];
				}
			}
			
			$this->assign('group', $group);
			return $this->fetch();
		}
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
	
	public function deleteResource(){
		$resource = new \eh\Resource();
		if ($resource->delete(input('resourceID')) === TRUE){
			$this->successResult('资源删除成功');
		}else{
			$this->successResult($resource->getError() ?: '资源删除失败');
		}
	}
	
	public function deleteResources(){
		$resource = new \eh\Resource();
		if ($resource->delete(input('resourceID/a')) === TRUE){
			$this->successResult('资源删除成功', ['delete' => $resource->getIds()]);
		}else{
			$this->successResult($resource->getError() ?: '资源删除失败');
		}
	}
}