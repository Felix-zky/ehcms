<?php
namespace app\admin\model;

use think\Model;
class Resource extends Model{
	
	public function getTypeAttr($value){
		$type = [0 => '其他', 1 => '图片', 2 => '压缩包', 3 => '音频文件', 4 => '视频文件', 5 => '文档文件', 6 => '网页文件', 7 => '可执行文件'];
		return $type[$value];
	}
	
}