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
 * 管理员权限分组
 */
class AdminGroup extends Init{

    /**
     * 管理员权限分组列表页面
     */
	public function index(){
	    $group = db('admin_group')->field('keys', true)->order('id', 'desc')->paginate(20);
	    $this->assign('group', $group);
		return $this->fetch();
	}

    /**
     * 创建管理员权限分组页面
     */
	public function create(){
		$this->assign('actionSign', 'editor');
		return $this->fetch('editor');
	}

    /**
     * 编辑管理员权限分组页面
     * @param int $id 权限分组编号
     */
    public function edit($id){
        $this->assign('groupID', $id);
        $this->assign('actionSign', 'editor');
        return $this->fetch('editor');
    }

    /**
     * 管理员权限分组新增
     */
	public function save(){
	    if (db('admin_group')->insert(input()) == 1){
	        $this->successResult('权限新增成功');
        }else{
	        $this->errorResult('权限新增失败');
        }
	}

    /**
     * 管理员权限分组更新
     * @param integer $id 权限分组编号
     */
    public function update($id){
        if (db('admin_group')->where('id', $id)->update(input()) == 1){
            $this->successResult('权限更新成功');
        }else{
            $this->errorResult('权限更新失败');
        }
    }

    /**
     * 获取所有权限
     */
	public function getPermission(){
	    $groupID = input('group');
        $group = [];

        //当groupID存在时，查询当前分组权限内容（在编辑页面使用）
        if (!empty($groupID)){
            $groupArr = db('admin_group')->field(['name', 'keys'])->where('id', $groupID)->find();
            $groupKeys = explode(',', $groupArr['keys']);
            $group['name'] = $groupArr['name'];
        }

        //查询全部模块
		$module = db('admin_module')->select();
        //查询所有权限
		$permissionResult = db('admin_permission')->field('p.id, p.group_id, p.name, p.key, pg.name as group_name, pg.module_id')->alias('p')->join('admin_permission_group pg', 'pg.id = p.group_id')->select();

		//模块重组，方便后期匹配
        $moduleRelation = [];
		foreach ($module as $k=>$m){
            $moduleRelation[$m['id']] = $k;
        }

        //组合模块及权限数据
		$permission = [];
		foreach ($permissionResult as $v){
			if (empty($permission[$v['module_id']][$v['group_id']])){
                $permission[$v['module_id']][$v['group_id']] = [
                    'name' => $v['group_name'],
                    'permission' => []
                ];
			}

            $permission[$v['module_id']][$v['group_id']]['permission'][$v['id']] = [
                'name' => $v['name'],
                'key' => $v['key']
            ];

			if (!empty($groupKeys) && is_array($groupKeys) && in_array($v['key'], $groupKeys)){
			    $module[$moduleRelation[$v['module_id']]]['permissionName'][] = $v['name'];
                $permission[$v['module_id']][$v['group_id']]['permission'][$v['id']]['selected'] = TRUE;
            }
		}

        foreach ($module as &$m){
            if (!empty($m['permissionName'])){
                $m['permissionName'] = join('，', $m['permissionName']);
            }
        }

        $data = [
            'module' => $module,
            'permission' => $permission,
            'group' => $group
        ];
		
		$this->successResult($data);
	}
}