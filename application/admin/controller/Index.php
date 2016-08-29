<?php
namespace app\admin\controller;

class Index extends Base{
	public function __construct(){
		parent::__construct();
		
		$this->view->engine->layout(FALSE);
	}
	
	public function index(){
		//查询桌面图标
		$this->assign('desktop', db('admin_desktop')->select());
		return $this->fetch();
	}
	
	public function getMenu(){
		$post = input('');
		
		$level1 = db('admin_menu')->where('id', 'in', $post['ids'])->order('sort desc')->select();
		
		foreach ($level1 as $l1){
			$parentID[] = $l1['id'];
			$menu[$l1['id']]['name'] = $l1['name'];
		}
		
		$level2 = db('admin_menu')->where('parent_id', 'in', $parentID)->order('sort desc')->select();

		
		foreach ($level2 as $l2){
			$menu[$l2['parent_id']]['child']['name'] = $l2['name'];
			$menu[$l2['parent_id']]['child']['url'] = $l2['url'];
			$menu[$l2['parent_id']]['child']['icon'] = $l2['icon'];
		}
		
		$menu['isData'] = TRUE;
		return $this->ajaxSuccessResult($menu);
	}
	
}

?>