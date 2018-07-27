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

    public function micro(){
        require_once EXTEND_PATH . "wechat_pay/lib/WxPay.Api.php";
        require_once EXTEND_PATH . "wechat_pay/micro_pay/WxPay.MicroPay.php";
        require_once EXTEND_PATH . "wechat_pay/micro_pay/WxPay.Config.php";
        require_once EXTEND_PATH . "wechat_pay/lib/WxPay.Config.Interface.php";

        $config = new \WxPayConfig();

        print_r($config);
        die;

        $data = [
            'uid' => cookie('user_id'),
            'order_id' => Date('Y').(int)THINK_START_TIME.cookie('user_id'),
            'order_total_fee' => input('price'),
            'order_body' => '商品支付',
            'pay_type' => 'weixin',
            'status' => 1,
            'create_time' => (int)THINK_START_TIME
        ];

        if (db('cashier_order')->insert($data) != 1){
            $this->errorResult('订单生成失败');
        }

        $micro = input('micro');

        $input = new \WxPayMicroPay();
        $input->SetAuth_code($micro);
        $input->SetBody("商品支付");
        $input->SetTotal_fee($data['price']);
        $input->SetOut_trade_no($data['order_id']);

        $microPay = new \MicroPay();
        printf_info($microPay->pay($input));
    }
}