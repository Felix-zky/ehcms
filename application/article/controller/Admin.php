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

namespace app\article\controller;

use app\admin\controller\Base;
/**
 * 文章模块后台控制器
 */
class Admin extends Base{
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 获取文章列表
	 */
	public function index(){		
		return $this->fetch();
	}
	
}