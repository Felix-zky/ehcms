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
	
	/**
	 * 文件接收器，所有资源的上传入口。
	 */
	public function receiver(){
		//获取文件，目前仅支持单文件，后期再加多文件的处理
		$file = request()->file('file');
		
		//组合文件上传目录
		$post = input('post.');
		$base = 'uploader/';
		
		if (!empty($post['uploaderPath'])){
			$path = $base . $post['uploaderPath'];
		}elseif (!empty($post['uploaderType'])){
			$path = $base . $post['uploaderType'];
		}else{
			$path = $base . 'default';
		}
		
		$info = $file->move($path);
		
		echo $info->getExtension()."\n";
		echo $info->getBasename()."\n";
		echo $info->getFileInfo()."\n";
		echo $info->getFilename ()."\n";
		echo $info->getLinkTarget()."\n";
		echo $info->getPath()."\n";
		echo $info->getPathInfo()."\n";
		echo $info->getPathname()."\n";
		echo $info->getRealPath();
	}
}