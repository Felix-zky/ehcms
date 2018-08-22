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

namespace app\admin\model;

use think\Model;
class CashierOrder extends Model{

    public function getStatusAttr($value){
        $status = [1=>'已支付',0=>'未支付',2=>'已关闭'];
        return $status[$value];
    }

    public function getPayTypeAttr($value){
        $status = ['weixin'=>'微信','alipay'=>'支付宝'];
        return $status[$value];
    }

}