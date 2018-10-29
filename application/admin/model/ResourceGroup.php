<?php
namespace app\admin\model;
use think\Model;

/**
 * 资源分类模型
 */
class ResourceGroup extends Model{

    /**
     * 获取关联的资源编号
     */
	public function resource(){
		return $this->hasMany('Resource', 'group_id', 'id')->field('id');
	}
	
}