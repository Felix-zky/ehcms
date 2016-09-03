<?php
// +----------------------------------------------------------------------
// | ehcms [ Efficient Handy Content Management System ]
// +----------------------------------------------------------------------
// | Copyright (c) http://ehcms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zky < zky@ehcms.com >
// +----------------------------------------------------------------------

namespace app\resource\controller;

/**
 * 资源模块-上传资源
 *
 */
class Uploader{
	//图片类型的编号及允许范围
	const TYPE_IMAGE_NUMBER = 1;
	
	private $typeImageExtension = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
	
	/**
	 * 文件接收器，所有资源的上传入口。
	 * @return json 包括成功标识、资源ID、资源绝对地址
	 */
	public function receiver(){
		//获取文件，目前仅支持单文件，后期再加多文件的处理
		$file = request()->file('file');
		$post = input('post.');
		
		$type = $this->checkExtension($post['extension']);
		
		if (!!$type === FALSE){
			//$this->
		}
		
		//组合文件上传目录
		$post = input('post.');
		$path = 'uploader/';
		
		$info = $file->move($path);
		
		$data['extension'] = $info->getExtension();
		$data['path'] = $info->getPathname();
		
		//db('resource_transfer')->insert($data)
	}
	
	/**
	 * 检查当前文件的后缀是否在允许范围中
	 * @param string $extension 后缀名称
	 * @return int|boolean 在范围内，返回该访问的类型ID，不在范围内返回false。
	 */
	private function checkExtension($extension){
		if (in_array($extension, $this->$typeImageExtension)){
			return self::TYPE_IMAGE_NUMBER;
		}
		
		return false;
	}
}