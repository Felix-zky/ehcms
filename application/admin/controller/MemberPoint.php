<?php
namespace app\admin\controller;

class MemberPoint extends Init{
	
	public function index(){
		return $this->fetch();
	}
	
	public function edit(){
		if (request()->isPost()){
			$id = input('id');
			$point = (int)input('point');
			
			if ($point > 0){
				$result = db('member')->where('id', $id)->setInc('point', $point);
				
			}else if ($point < 0){
				$result = db('member')->where('id', $id)->setDec('point', abs($point));
			}else{
				$this->error('积分值不正确');
			}
			
			if ($result == 1){
				$this->success('积分更新成功');
			}else{
				$this->error('积分更新失败');
			}
		}
		
		return $this->fetch();
	}
	
	public function bind(){
		if (request()->isPost()){
			$uid = input('id');
			$cardNumber = input('point_card');
			
			$data = [
				'id' => $uid,
				'point_card' => $cardNumber
			];
			
			$cardData = [
				'uid' => $uid,
				'card_number' => $cardNumber,
				'create_time' => THINK_START_TIME
			];
			
			$result = db('point_card')->where('card_number', $cardNumber)->find();
			
			if ($result){
				$this->error('该积分卡已被绑定，请更换！');
			}
			
			if (db('member')->update($data) == 1){
				db('point_card')->insert($cardData);
				$this->success('绑定成功！');
			}else{
				$this->error('绑定失败！');
			}
		}
		
		return $this->fetch();
	}

	public function getMember(){
		$data = input('data');
		if (!$data){
			$this->errorResult('查询条件错误');
		}
		
		if (input('type') == 'card'){
			$where = [
				'point_card' => $data
			];
		}else{
			$where = [
					'phone|id_number' => $data
			];
		}
		
		$member = action('member/User/getSingleUser', ['where' => $where, 'field'=>'id,true_name'], 'event');
		
		if ($member){
			$this->successResult($member);
		}else{
			$this->errorResult('用户不存在');
		}
	}
	
	private function log(){
		
	}
}