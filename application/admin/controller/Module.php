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
 * 模块管理
 */
class Module extends Init{

    /**
     * 模块管理页面
     */
	public function index(){
        $module = db('admin_module')->order('id', 'desc')->paginate(10);
		
		$this->assign('module', $module);
		return $this->fetch();
	}

    /**
     * 模块创建页面
     */
	public function create(){
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}

    /**
     * 新增模块
     */
	public function save(){
		$data = input('param.');
		if (db('admin_module')->insert($data) == 1){
			$this->successResult();
		}else{
			$this->errorResult();
		}
	}

    /**
     * 编辑模块
     * @param int $id 模块编号
     */
	public function edit($id){
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}
}