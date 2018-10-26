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

class Index extends Init{
	
	public function __construct(){
		parent::__construct();
		
		$this->view->engine->layout(FALSE);
	}
	
	/**
	 * 后台首页，读取桌面图标。
	 */
	public function index(){
	    $userID = cookie('user_id');
		//查询桌面图标
        if ($userID == 1){
            $desktop = db('admin_module')->select();
        }else{
            $desktop = db('admin_module')->where('id', 'in', $this->powerIds)->select();
        }
		$this->assign('desktop', $desktop);
		return $this->fetch();
	}
	
	/**
	 * 异步方法，读取应用菜单。
	 */
	public function getMenu(){
		$post = input('post.');
		
		$level1 = db('admin_permission_group')->where(['module_id' => $post['moduleID']])->select();
		
		foreach ($level1 as $l1){
			$groupID[] = $l1['id'];
			$menu[$l1['id']]['name'] = $l1['name'];
		}
		
		$level2 = db('admin_permission')->where('group_id', 'in', $groupID)->where('is_menu', 1)->select();
		
		foreach ($level2 as $l2){
			$data = [
				'name' => $l2['name'],
				'url'  => url($l2['menu_url']),
				'icon' => $l2['menu_icon'],
                'key' => $l2['key']
			];
			
			$menu[$l2['group_id']]['child'][] = $data;
		}
		
		$menu = array_values($menu);
		
		return $this->successResult($menu);
	}
}

?>