<?php
namespace app\member\model;

use think\Model;
class Ceshi extends Model{
	protected $table = 'eh_member';
	protected $autoWriteTimestamp = true;
	
	public function getCreateTimeAttr($value){
		return date('Y-m-d H:i:s', $value);
	}
}