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
	
}