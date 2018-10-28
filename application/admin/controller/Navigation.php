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
 * 导航模块后台控制器
 */
class Navigation extends Init{

    /**
     * 导航列表页面
     */
    public function index(){
        $navigation = db('navigation')->order(['sort' => 'desc', 'id' => 'desc'])->paginate(20);
        $this->assign('navigation', $navigation);
        return $this->fetch();
    }

    /**
     * 导航创建页面
     */
    public function create(){
        $this->assign('actionSign', 'editor');
        return $this->fetch('editor');
    }

    /**
     * 新增导航
     */
    public function save(){
        $data = input('param.');

        $data['client_type'] = !empty($data['client_type']) ? 1 : 2;
        $data['is_blank'] = !empty($data['is_blank']) ? 1 : 0;

        $navigation = db('navigation')->field('sort')->order('sort', 'desc')->find();
        $data['sort'] = !empty($navigation) ? $navigation['sort'] + 1 : 1;

        if (db('navigation')->insert($data) == 1){
            $this->success('导航添加成功');
        }else{
            $this->error('导航添加失败');
        }
    }

    /**
     * 导航编辑页面
     * @param int $id 导航编号
     */
    public function edit($id){
        $navigation = db('navigation')->where('id', $id)->find();
        $this->assign('id', $id);
        $this->assign('navigation', $navigation);
        $this->assign('actionSign', 'editor');
        return $this->fetch('editor');
    }

    /**
     * 编辑导航
     * @param int $id 导航编辑
     */
    public function update($id){
        $data = input('param.');

        unset($data['_method']);

        $data['client_type'] = !empty($data['client_type']) ? 1 : 2;
        $data['is_blank'] = !empty($data['is_blank']) ? 1 : 0;

        db('navigation')->where('id', $id)->update($data);
        $this->success('导航更新成功', 'admin/Navigation/index');
    }

    /**
     * 删除导航
     * @param int $id 导航编号
     */
    public function delete($id){
        if (db('navigation')->where('id', $id)->delete() == 1){
            $this->successResult('导航删除成功');
        }else{
            $this->errorResult('导航删除失败');
        }
    }

    /**
     * 导航更新排序顺序
     */
    public function sort(){
        $type = input('type');
        $id = input('id');

        $navigation = model('navigation');

        $current = $navigation->where('id', $id)->find();
        if ($type == 'up'){
            $up = $navigation->where('sort', 'GT', $current->sort)->find();
            if (!$up){
                $this->errorResult('已经是第一个了');
            }else{
                $data = [
                    ['id' => $current->id, 'sort' => $up->sort],
                    ['id' => $up->id, 'sort' => $current->sort]
                ];
                $navigation->saveAll($data);
                $this->successResult('排序更新成功');
            }
        }else{
            $down = $navigation->where('sort', 'LT', $current->sort)->find();
            if (!$down){
                $this->errorResult('已经是最后一个了');
            }else{
                $data = [
                    ['id' => $current->id, 'sort' => $down->sort],
                    ['id' => $down->id, 'sort' => $current->sort]
                ];
                $navigation->saveAll($data);
                $this->successResult('排序更新成功');
            }
        }
    }
}