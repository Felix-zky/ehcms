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
 * 订单
 */
class Order extends Init{

    /**
     * 订单列表页面
     */
	public function index(){
		$order = model('order')->order('id', 'desc')->paginate(10);
	
		$this->assign('order', $order);
		return $this->fetch();
	}

    /**
     * 积分兑换提交及对应页面
     */
	public function point(){
		if (request()->isPost()){
			$phone = input('phone');
			$code = input('code');
			
			if (empty($phone) || empty($code)){
				$this->errorResult('核销失败（缺少参数）');
			}
			
			$order = Db::name('order')->where(['phone' =>$phone, 'exchange_code'=> $code])->find();
			
			if (!$order){
				$this->errorResult('核销失败（手机号或者兑换码有误）');
			}
			
			if ($order['status'] == 1){
				$this->errorResult('核销失败（订单已兑换）');
			}
			
			if (Db::name('order')->where('id', $order['id'])->update(['status' => 1, 'finish_time' => THINK_START_TIME]) == 1){
				if (is_numeric($order['goods_id'])){
					$data['goods'][0] = Db::name('goods')->field('title, thumbnail')->where('id', $order['goods_id'])->find();
				}
				
				$this->successResult('订单核销成功', $data);
			}else{
				$this->errorResult('订单核销失败');
			}
		}else{
			return $this->fetch();
		}
	}
	
}