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
	/**
	 * 获取文章列表
	 */
	public function index(){
		$pages = (int)input('param.pages');
		if ($pages){
			if ($pages > 0){
				$article = db('article')->page($pages, 20)->select();
				
				if (count($article) > 0){
					$data = [
						'articles' => $article,
						'count' => db('article')->count()
					];
					
					return $this->successResult($data);
				}else{
					return $this->errorResult('E-030201');
				}
			}else{
				return $this->errorResult('E-030106');
			}
		}else{
			return $this->fetch();
		}
	}
	
	
	/**
	 * 新增文章
	 */
	public function create(){
		$this->assign('category', $this->getCategory());
		$this->assign('saveUrl', url('save'));
		return $this->fetch();
	}
	
	/**
	 * 保存（新增入库）文章
	 */
	public function save(){
		if (request()->isPost()){
			if (db('article')->insert(array_merge(input('param.'), ['uid' => cookie('user_id'), 'create_time' => THINK_START_TIME])) == 1){
				$this->successResult('文章发布成功', '/admin/article');
			}else{
				$this->errorResult('文章发布失败');
			}
		}else{
			$this->errorResult('E-03002');
		}
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
	
	/**
	 * 文章资源上传，缩略图和正文资源暂一起上传，后期将缩略图分离出来做判断。
	 */
	public function resource(){
		$file = request()->file('file');
		
		if (is_object($file)){
			$resource = new \eh\Resource();
			$result = $resource->uploader($file, (int)input('groupID'));
			
			if ($result > 0){
				$this->successResult('上传成功', ['url' => $resource->getData('url')]);
			}else{
				$this->errorResult($resource->getError());
			}
		}else{
			$this->errorResult('获取上传文件失败');
		}
	}
	
	public function getCategory(){
		$parentID = input('parent_id') ?: 0;
		$result = db('article_category')->where('parent_id', $parentID)->order('id', 'desc')->select();
		if (request()->isAjax()){
			$this->successResult(['category' => $result]);
		}else{
			return $result;
		}
	}
}