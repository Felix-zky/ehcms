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
	 */
	public function receiver(){
		//获取文件，目前仅支持单文件，后期再加多文件的处理
		$file = request()->file('file');
		
		unlink('./' . 'uploader/article/20160902/b6bfbf0e93f5e05ffaad3e927baa9805.png');
		
		//print_r($file);
		die;
		
		//组合文件上传目录
		$post = input('post.');
		$path = 'uploader/';
		
		$info = $file->move($path);
		
		$data['extension'] = $info->getExtension();
		$data['path'] = $info->getPathname();
		
		//db('resource_transfer')->insert($data)
	}
	
	private function checkExtension($extension){
		if (in_array($extension, $this->$typeImageExtension)){
			return self::TYPE_IMAGE_NUMBER;
		}
		
		return false;
	}
}