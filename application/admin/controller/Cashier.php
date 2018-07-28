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
use think\Db;

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

    public function weixin(){
        require_once EXTEND_PATH . "wechat_pay/lib/WxPay.Api.php";
        require_once EXTEND_PATH . "wechat_pay/micro_pay/WxPay.MicroPay.php";
        require_once EXTEND_PATH . "wechat_pay/micro_pay/WxPay.Config.php";
        require_once EXTEND_PATH . "wechat_pay/lib/WxPay.Config.Interface.php";

        $config = new \WxPayConfig();

        if (empty($config->GetAppId())){
            $this->errorResult('订单生成失败，配置错误！');
        }

        $data = [
            'uid' => cookie('user_id'),
            'order_id' => Date('Y').(int)THINK_START_TIME.cookie('user_id'),
            'order_name' => '商品支付',
            'order_total_fee' => input('price'),
            'order_body' => '商品支付',
            'pay_type' => 'weixin',
            'status' => 1,
            'create_time' => (int)THINK_START_TIME
        ];

        if (Db::name('cashier_order')->insert($data) != 1){
            $this->errorResult('订单生成失败');
        }

        $insert_id = Db::name('cashier_order')->getLastInsID();

        $authCode = input('auth_code');

        $input = new \WxPayMicroPay();
        $input->SetAuth_code($authCode);
        $input->SetBody("商品支付");
        $input->SetTotal_fee(1);
        $input->SetOut_trade_no($data['order_id']);

        $microPay = new \MicroPay();
        $pay = $microPay->pay($config, $input);

        if ($pay == false){
            $this->errorResult('订单生成失败');
        }else{
            if (!empty($pay['err_code']) && ($pay['err_code'] == 'USERPAYING' || $pay['err_code'] == 'SYSTEMERROR')){
                $this->successResult(['id' => $insert_id, 'order_id' => $data['order_id'], 'code' => $pay['err_code']]);
            }elseif($pay['result_code'] == 'SUCCESS'){
                if (($pay['total_fee'] / 100) != $data['order_total_fee'] || $pay['out_trade_no'] != $data['order_id']){
                    $this->errorResult('支付成功，但信息不符，请核查用户手机支付结果！');
                }

                $data = [
                    'buyer' => $pay['openid'],
                    'status' => 2,
                    'pay_order_id' => $pay['transaction_id'],
                    'finish_time' => strtotime($pay['time_end'])
                ];

                if (Db::name('cashier_order')->where('id', $insert_id)->update($data) == 1){
                    $this->successResult('订单支付成功', ['code' => 'SUCCESS']);
                }else{
                    $this->errorResult('订单支付成功，但信息更新失败，请核查用户手机支付结果！');
                }
            }else{
                $this->errorResult('支付失败，请核查用户手机支付结果！');
            }
        }
    }

    public function weixinQuery(){
        require_once EXTEND_PATH . "wechat_pay/lib/WxPay.Api.php";
        require_once EXTEND_PATH . "wechat_pay/micro_pay/WxPay.Config.php";

        $id = input('id');
        $order_id = input('order_id');

        $queryOrderInput = new \WxPayOrderQuery();
        $queryOrderInput->SetOut_trade_no($order_id);
        $config = new \WxPayConfig();

        $result = \WxPayApi::orderQuery($config, $queryOrderInput);

        if($result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            //支付成功
            if($result["trade_state"] == "SUCCESS"){
                $data = [
                    'buyer' => $order_id,
                    'status' => 2,
                    'pay_order_id' => $result['transaction_id'],
                    'finish_time' => strtotime($result['time_end'])
                ];

                if (Db::name('cashier_order')->where('id', $id)->update($data) == 1){
                    $this->successResult(['code' => 1]);
                }
            }
            //用户支付中
            else if($result["trade_state"] == "USERPAYING"){
                $this->successResult(['code' => 2]);
            }
        }

        //如果返回错误码为“此交易订单号不存在”则直接认定失败
        if($result["err_code"] == "ORDERNOTEXIST")
        {
            $this->successResult(['code' => 0]);
        } else{
            $this->successResult(['code' => 2]);
        }
    }

    public function weixinCancel(){
        require_once EXTEND_PATH . "wechat_pay/lib/WxPay.Api.php";
        require_once EXTEND_PATH . "wechat_pay/micro_pay/WxPay.Config.php";

        $id = input('id');
        $order_id = input('order_id');

        $closeOrder = new \WxPayReverse();
        $closeOrder->SetOut_trade_no($order_id);

        $config = new \WxPayConfig();
        $result = \WxPayApi::reverse($config, $closeOrder);

        //接口调用失败
        if($result["return_code"] != "SUCCESS"){
            $this->errorResult('订单撤销失败，请重试');
        }

        $data = [
            'status' => 0,
            'finish_time' => (int)THINK_START_TIME
        ];

        //如果结果为success且不需要重新调用撤销，则表示撤销成功
        if($result["result_code"] == "SUCCESS"
            && $result["recall"] == "N"){
            if (Db::name('cashier_order')->where('id', $id)->update($data) == 1){
                $this->successResult('订单撤销成功');
            }
        }else if($result["recall"] == "Y") {
            $this->errorResult('订单撤销失败，请重试');
        }else{
            $this->errorResult('订单撤销失败');
        }
    }
}