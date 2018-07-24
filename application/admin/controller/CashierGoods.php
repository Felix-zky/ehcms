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

class CashierGoods extends Init{

    public function index(){
        $goods = db('cashier_goods')->order('id', 'desc')->paginate(24);
        $this->assign('goods', $goods);
        return $this->fetch();
    }

    public function create(){
        $category = db('cashier_goods_category')->select();
        $this->assign('category', $category);
        $this->assign('actionSign', 'editor');
        return $this->fetch('editor');
    }

    public function save(){
        $data = input();
        $data['create_time'] = THINK_START_TIME;

        $data['type'] = !empty($data['type']) ? 1 : 2;

        if (db('cashier_goods')->insert($data) == 1){
            $this->successResult('商品发布成功');
        }else{
            $this->errorResult('商品发布失败');
        }
    }

    public function edit($id){
        $goods = db('cashier_goods')->where('id', $id)->find();
        $categoryID = $goods['category_id'];

        $this->assign('goods', $goods);
        $this->assign('category', $this->getCategory());
        $this->assign('categoryID', $categoryID);
        $this->assign('goodsID', $id);
        $this->assign('actionSign', 'editor');
        return $this->fetch('editor');
    }

    public function update($id){
        $data = input('param.');

        $data['type'] = !empty($data['type']) ? 1 : 2;

        if (request()->isPut()){
            if (db('cashier_goods')->where('id', $id)->update($data) == 1){
                $this->successResult('商品更新成功', ['redirect_url' => url('admin/CashierGoods/index')]);
            }else{
                $this->errorResult('商品更新失败');
            }
        }else{
            $this->errorResult('E-03002');
        }
    }

    /**
     * 上传缩略图及商品组图
     */
    public function resource(){
        $file = request()->file('file');

        if (is_object($file)){
            $resource = new \eh\Resource();
            $result = $resource->uploader($file, (int)input('groupID'));

            if ($result > 0){
                $this->successResult('上传成功', ['url' => $resource->getData('url')]);
            }else{
                $this->errorResult($resource->getError());
            }
        }else{
            $this->errorResult('获取上传文件失败');
        }
    }

    private function getCategory(){
        $result = db('cashier_goods_category')->select();
        return $result;
    }
}