<?php
namespace app\admin\controller;

class Document extends Init{
	
	public function index(){
		$document = db('document')->field('id, name, cover')->order('id', 'desc')->paginate(18);
		$this->assign('document', $document);
		return $this->fetch();
	}
	
	public function create(){
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}
	
	public function save(){
		$data = input('param.');
		if (db('document')->insert($data) == 1){
			$this->successResult('文档新增成功，请继续编辑文档内容！');
		}else{
			$this->errorResult('文档新增失败');
		}
	}
	
	public function edit($id){
		$this->assign('id', $id);
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}
	
	public function update(){
		
	}
	
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
	
	public function getItems(){
		$items = db('document_item')->where('document_id', input('document_id'))->select();
		if ($items){
			$data = [];
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
			$this->successResult(['items' => $data]);
		}
	}
	
	public function addItem(){
		$data = input('post.');
		$returnData = [];
		
		if (!empty($data['type']) && $data['type'] == 'on'){
			$data['type'] = 1;
			$successMsg = '文章类型新增成功';
			
			if (is_numeric($data['name'])){
				$data['relation_id'] = $data['name'];
				$result = db('article')->field('title')->where('id', $data['name'])->find();
				if ($result){
					$data['name'] = $result['title'];
					$returnData['name'] = $result['title'];
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
	
}