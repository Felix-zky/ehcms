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
class Cashier extends Init{

    public function index(){
        $category = db('cashier_goods_category')->select();
        $goods = db('cashier_goods')->order('id', 'desc')->select();
        $this->assign('goods', $goods);
        $this->assign('category', $category);
        return $this->fetch();
    }

    public function getGoods(){
        $category = input('category');
        $keyword = input('keyword');

        if (!empty($category) && !empty($keyword)) {
            $goods = db('cashier_goods')->where(['category_id' => $category, 'title|short' => ['like', '%'.$keyword.'%']])->order('id', 'desc')->select();
        }elseif (empty($category) && !empty($keyword)) {
            $goods = db('cashier_goods')->where('title|short', 'like', '%'.$keyword.'%')->order('id', 'desc')->select();
        }elseif(!empty($category)){
            $goods = db('cashier_goods')->where('category_id', $category)->order('id', 'desc')->select();
        }else{
            $goods = db('cashier_goods')->order('id', 'desc')->select();
        }

        $this->successResult($goods);
    }

    public function goods($id){
        $goods = db('cashier_goods')->where('id', $id)->find();
        if ($goods){
            $this->successResult('goods', $goods);
        }else{
            $this->errorResult('无法识别商品');
        }
    }
}