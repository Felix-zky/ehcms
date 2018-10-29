<?php
namespace app\admin\model;
use think\Model;

/**
 * 权限分组模型
 */
class PermissionGroup extends Model{	
	protected $table = 'eh_admin_permission_group';

    /**
     * 获取关联模块的名称
     */
	public function module(){
        return $this->hasOne('module', 'id', 'module_id')->field('name');
    }
}