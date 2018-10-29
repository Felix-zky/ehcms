<?php
namespace app\admin\model;
use think\Model;

/**
 * 用户记录模型
 */
class PointLog extends Model{

    /**
     * 获取关联的用户名和真实姓名
     */
	public function user(){
		return $this->hasOne('member', 'id', 'uid')->field('username, true_name');
	}

    /**
     * 获取关联的管理员用户名
     */
	public function admin(){
		return $this->hasOne('member', 'id', 'admin_id')->field('username');
	}

    /**
     * 记录类型获取器
     */
	public function getTypeAttr($value){
		$type = [1 => '增加', 2 => '减少', 3 => '商品兑换'];
		return $type[$value];
	}
}