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

class Setting extends Init{
	
	public function system(){
		return $this->fetch();
	}
	
	public function personal(){
		return $this->fetch();
	}
	
	public function systemEdit(){
		if (request()->isPut()){
			if (db('admin_setting')->where(['uid' => 0, 'key' => input('key')])->update(['value' => input('value')]) == 1){
				$this->successResult();
			}
		}
	}
	
	public function personalEdit(){
		if (request()->isPut()){
			$result = db('admin_setting')->where(['uid' => cookie('user_id'), 'key' => input('key')])->find();
			if ($result){
				if (db('admin_setting')->where('id', $result['id'])->update(['value' => input('value')]) == 1){
					$this->successResult();
				}
			}else{
				if (db('admin_setting')->insert([
					'uid' => cookie('user_id'),
					'key' => input('key'),
					'value' => input('value')
				]) == 1){
					$this->successResult();
				}
			}
		}
	}

	public function alipay(){
	    return $this->fetch();
    }

    public function weixin(){
        return $this->fetch();
    }
}