<?php
namespace app\admin\model;

use think\Model;
class Resource extends Model{
	
	public function getTypeAttr($value){
		$type = [1 => '图片', 2 => '压缩包', 3 => '执行文件'];
		return $type[$value];
	}
	
}