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
		$file = request()->file('file');
		
		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'article');
		
		echo $info->getPath();
	}
}