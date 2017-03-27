<?php
namespace app\admin\model;

use think\Model;
class ResourceGroup extends Model{
	
	public function resource(){
		return $this->hasMany('Resource', 'group_id', 'id')->field('id');
	}
	
}