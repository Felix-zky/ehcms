<?php
namespace app\admin\model;
use think\Model;

/**
 * 权限模型
 */
class Permission extends Model{
	protected $table = 'eh_admin_permission';

    /**
     * 菜单获取器
     */
	public function getIsMenuAttr($value){
		$status = [1=>'是', 0=>'否'];
		return $status[$value];
	}
	
}