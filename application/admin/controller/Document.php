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
use think\Db;

/**
 * 文档书库
 */
class Document extends Init{

    /**
     * 文档书库列表
     */
	public function index(){
		$document = db('document')->field('id, name, cover')->order('id', 'desc')->paginate(18);
		$this->assign('category', $this->getCategory());
		$this->assign('document', $document);
		return $this->fetch();
	}

    /**
     * 创建文档书库
     */
	public function create(){
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}

    /**
     * 保存文档书库
     */
	public function save(){
		$data = input('param.');
		$id = db('document')->insertGetId($data);
		if ($id > 0){
			$this->successResult('文档新增成功，请继续编辑文档内容！', ['id' => $id]);
		}else{
			$this->errorResult('文档新增失败');
		}
	}

    /**
     * 文档编辑页面
     * @param int $id 文档编号
     */
	public function edit($id){
		$this->assign('id', $id);
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}

    /**
     * 文档更新
     */
	public function update(){
		
	}

    /**
     * 上传文档资源
     */
	public function uploaderCover(){
		if (request()->isAjax()){
			$file = request()->file('file');
				
			if (is_object($file)){
				$resource = new \eh\Resource(FALSE, 'uploader'. DS . 'document');
				$result = $resource->uploader($file);
		
				if ($result > 0){
					$this->successResult('上传成功', ['resourceID' => $result, 'url' => $resource->getData('url')]);
				}else{
					$this->errorResult($resource->getError());
				}
			}else{
				$this->errorResult('获取上传文件失败');
			}
		}
	}

    /**
     * 获取文档目录
     */
	public function getItems(){
		$items = db('document_item')->where('document_id', input('document_id'))->select();
		$data = [];
		if ($items){
			foreach ($items as $item){
				$data[] = [
					'id' => 'tree' . $item['id'],
					'text' => $item['name'],
					'parent' => $item['parent_id'] == 0 ? '#' : 'tree' . $item['parent_id'],
					'li_attr' => [
						'data-type' => $item['type']
					]
				];
			}
		}
		
		$this->successResult(['items' => $data]);
	}

    /**
     * 新增文档目录
     */
	public function addItem(){
		$data = input('post.');
		$returnData = [];
		
		if (!empty($data['type']) && $data['type'] == 'on'){
			$data['type'] = 1;
			$successMsg = '文章类型新增成功';
			
			if (is_numeric($data['name'])){
				$data['relation_id'] = $data['name'];
				$result = Db::name('article')->field('title')->where(['id' => $data['name'], 'document_id' => 0])->find();
				if ($result){
					$data['name'] = $result['title'];
					$returnData['name'] = $result['title'];

                    Db::name('article')->where('id', $data['relation_id'])->update(['document_id' => $data['document_id']]);
				}else{
					$this->errorResult('文章不存在或已被其他文档使用');
				}
			}
		}else{
			$data['type'] = 2;
			$successMsg = '目录类型新增成功';
		}
		
		$id = db('document_item')->insertGetId($data);
		if ($id > 0){
			$returnData['id'] = $id;
			$this->successResult($successMsg, $returnData);
		}else{
			$this->errorResult('新增失败');
		}
	}

    /**
     * 获取文章
     */
	public function getArticle(){
		$itemID = input('id');
		$item = db('document_item')->field('relation_id')->where('id', $itemID)->find();
		$article = db('article')->field('content')->where('id', $item['relation_id'])->find();
		
		$data = [
			'article_id' => $item['relation_id'],
			'content' => $article['content']
		];
		
		$this->successResult($data);
	}

    /**
     * 获取文档分类
     */
	public function getCategory(){
		$parentID = input('parent_id') ?: 0;
		$result = db('document_category')->where('parent_id', $parentID)->order('id', 'desc')->select();
		if (request()->isAjax()){
			$this->successResult(['category' => $result]);
		}else{
			return $result;
		}
	}
	
}