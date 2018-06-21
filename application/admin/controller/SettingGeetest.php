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

class SettingGeetest extends Init{

	public function index(){
		return $this->fetch();
	}

    public function update(){
        if (request()->isPut()){
            if (db('admin_setting')->where(['uid' => 0, 'key' => input('key')])->update(['value' => input('value')]) == 1){
                $this->successResult();
            }
        }
    }
}