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

class CashierCategory extends Init{
    public function index(){
        $category = db('cashier_goods_category')->paginate(20);
        $this->assign('category', $category);
        return $this->fetch();
    }

    public function save(){
        if (db('cashier_goods_category')->insert(input('post.')) == 1){
            $this->success('添加成功');
        }else{
            $this->error('添加失败');
        }
    }

    public function update($id){
        if (db('cashier_goods_category')->where('id', $id)->setField('name', input('name')) == 1){
            $this->success('更新成功');
        }else{
            $this->error('更新失败');
        }
    }
}