<?php
namespace app\admin\model;

use think\Model;
class PermissionGroup extends Model{	
	protected $table = 'eh_admin_permission_group';
	
	public function module(){
        return $this->hasOne('module', 'id', 'module_id')->field('name');
    }
}