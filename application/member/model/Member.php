<?php
namespace app\member\model;

use think\Model;
class Member extends Model{
	protected $autoWriteTimestamp = true;
	
	public function getCreateTimeAttr($value){
		return date('Y-m-d H:i:s', $value);
	}
}