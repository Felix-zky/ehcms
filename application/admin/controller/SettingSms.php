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
 * 短信接口配置
 * 默认采用阿里云短信接口
 */
class SettingSms extends Init{

    /**
     * 配置页面
     */
	public function index(){
		$sms = db('admin_setting')->where('key', 'aliyun_sms')->find();
		
		$sms['value'] = unserialize($sms['value']);
		
		$this->assign('sms', $sms['value']);
		return $this->fetch();
	}

    /**
     * 配置更新
     */
	public function update(){
		if (request()->isPut()){
			$data = [
				'name' => '阿里云短信接口配置',
				'key' => 'aliyun_sms',
				'value' => serialize(input('param.'))
			];
				
			$result = db('admin_setting')->where('key', 'aliyun_sms')->update($data);
				
			if ($result != 1){
				$this->errorResult('更新失败');
			}else{
				$this->successResult('更新成功');
			}
		}
	}
}