<?php
namespace app\admin\model;

use think\Model;
class PointLog extends Model{
	
	public function user(){
		return $this->hasOne('member', 'id', 'uid')->field('username');
	}
	
	public function admin(){
		return $this->hasOne('member', 'id', 'admin_id')->field('username');
	}
	
	public function getTypeAttr($value){
		$type = [1 => '增加', 2 => '减少', 3 => '商品兑换'];
		return $type[$value];
	}
}