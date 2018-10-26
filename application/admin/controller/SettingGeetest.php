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
 * 极验验证配置
 */
class SettingGeetest extends Init{

    /**
     * 配置页面
     */
	public function index(){
		return $this->fetch();
	}

    /**
     * 配置更新
     */
    public function update(){
        if (request()->isPut()){
            if (db('admin_setting')->where(['uid' => 0, 'key' => input('key')])->update(['value' => input('value')]) == 1){
                $this->successResult();
            }
        }
    }
}