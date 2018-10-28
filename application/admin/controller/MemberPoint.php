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
use Think\Db;

/**
 * 用户积分管理
 */
class MemberPoint extends Init{

    /**
     * 用户积分列表页面
     */
	public function index(){
		$log = model('PointLog')->order('id', 'desc')->paginate(20);
		$this->assign('log', $log);
		return $this->fetch();
	}

    /**
     * 用户积分更新及页面
     */
	public function edit(){
		if (request()->isPost()){
			$id = input('id');
			$point = (int)input('point');
			
			$member = Db::name('member')->where('id', $id)->find();
			
			if (!$member){
				$this->error('积分更新失败');
			}
			
			$data = [
				'uid' => $member['id'],
				'admin_id' => cookie('user_id'),
				'before' => $member['point'],
				'change' => $point,
				'remark' => input('remark'),
				'create_time' => THINK_START_TIME
			];
			
			if ($point > 0){
				$result = Db::name('member')->where('id', $id)->setInc('point', $point);
				$data['type'] = 1;
				$data['after'] = $member['point'] + $point;
			}else if ($point < 0){
				$point = abs($point);
				
				if ($member['point'] < $point){
					$this->error('积分不足' . $point . '分，无法扣除！');
				}
				
				$result = Db::name('member')->where('id', $id)->setDec('point', $point);
				$data['type'] = 2;
				$data['after'] = $member['point'] - $point;
			}else{
				$this->error('积分值不正确');
			}
			
			if ($result == 1){
				$this->log($data);
				$this->success('积分更新成功');
			}else{
				$this->error('积分更新失败');
			}
		}
		
		return $this->fetch();
	}

    /**
     * 绑定用户积分卡
     */
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
			
			$result = Db::name('point_card')->where('card_number', $cardNumber)->find();
			
			if ($result){
				$this->error('该积分卡已被绑定，请更换！');
			}
			
			if (Db::name('member')->update($data) == 1){
				Db::name('point_card')->insert($cardData);
				$this->success('绑定成功！');
			}else{
				$this->error('绑定失败！');
			}
		}
		
		return $this->fetch();
	}

    /**
     * 获取用户信息
     */
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

    /**
     * 新增用户积分记录
     * @param array $data 记录数据
     */
	private function log($data){
		Db::name('point_log')->insert($data);
	}
}