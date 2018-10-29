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

namespace app\admin\model;
use think\Model;

/**
 * 订单模型
 */
class Order extends Model{

    /**
     * 获取关联的用户名
     */
	public function member(){
		return $this->hasOne('Member', 'id', 'uid')->field('username');
	}

    /**
     * 订单总金额获取器
     */
	public function getOrderTotalFeeAttr($value, $data){
		return $data['order_type'] == 1 ? $value : (int)$value;
	}

    /**
     * 订单状态获取器
     */
	public function getStatusAttr($value, $data){
		$status = $data['order_type'] == 1 ? [1=>'已支付',0=>'未支付',2=>'已关闭'] : [1=>'已兑换',0=>'未兑换',2=>'已关闭'];
		return $status[$value];
	}

    /**
     * 订单支付状态获取器
     */
	public function getPayTypeAttr($value){
		$status = ['wx'=>'微信','ali'=>'支付宝','point'=>'积分'];
		return $status[$value];
	}

    /**
     * 订单类型获取器
     */
	public function getOrderTypeAttr($value){
		$status = [1=>'现金购买',2=>'积分兑换'];
		return $status[$value];
	}
	
}