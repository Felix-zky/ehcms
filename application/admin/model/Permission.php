<?php
namespace app\admin\model;

use think\Model;
class Permission extends Model{
	protected $table = 'eh_admin_permission';
	
	public function getIsMenuAttr($value){
		$status = [1=>'是', 0=>'否'];
		return $status[$value];
	}
	
}