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

namespace app\admin\controller;
/**
 * 文章模块后台控制器
 */
class Article extends Init{
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 获取文章列表
	 */
	public function index(){
		return $this->fetch();
	}
	
	
	/**
	 * 新增文章
	 */
	public function create(){
		return $this->fetch();
	}
	
	/**
	 * 保存（新增入库）文章
	 */
	public function save(){
		
	}
	
	/**
	 * 更新文章
	 */
	public function update($id){
		
	}
	
	/**
	 * 删除文章
	 */
	public function delete($id){
		
	}
	
	/**
	 * 文章回收站
	 */
	public function recycle(){
	
	}
}