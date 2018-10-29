<?php
namespace app\admin\model;
use think\Model;

/**
 * 资源模型
 */
class Resource extends Model{

    /**
     * 资源类型获取器
     */
	public function getTypeAttr($value){
		$type = [0 => '其他', 1 => '图片', 2 => '压缩包', 3 => '音频文件', 4 => '视频文件', 5 => '文档文件', 6 => '网页文件', 7 => '可执行文件'];
		return $type[$value];
	}
	
}