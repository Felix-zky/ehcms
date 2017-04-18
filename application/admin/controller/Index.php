<?php
namespace app\admin\controller;

class Index extends Init{
	
	public function __construct(){
		parent::__construct();
		
		$this->view->engine->layout(FALSE);
	}
	
	/**
	 * 后台首页，读取桌面图标。
	 */
	public function index(){
		//查询桌面图标
		//$this->assign('desktop', db('admin_desktop')->select());
		return $this->fetch();
	}
	
	/**
	 * 异步方法，读取应用菜单。
	 */
	public function getMenu(){
		$post = input('post.');
		
		$level1 = db('admin_menu')->where(['module_id' => $post['moduleID'], 'parent_id' => 0, 'is_show' => 1])->order('sort desc')->select();
		
		foreach ($level1 as $l1){
			$parentID[] = $l1['id'];
			$menu[$l1['id']]['name'] = $l1['name'];
		}
		
		$level2 = db('admin_menu')->where('parent_id', 'in', $parentID)->where('is_show', 1)->order('sort desc')->select();

		
		foreach ($level2 as $l2){
			$data = [
				'name' => $l2['name'],
				'url'  => url($l2['url']),
				'icon' => $l2['icon']
			];
			
			$menu[$l2['parent_id']]['child'][] = $data;
		}
		
		$menu = array_values($menu);
		
		return $this->successResult($menu);
	}
}

?>