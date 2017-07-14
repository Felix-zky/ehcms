<?php
namespace app\admin\controller;

class SettingSms extends Init{
	
	public function index(){
		$sms = db('admin_setting')->where('key', 'aliyun_sms')->find();
		
		$sms['value'] = unserialize($sms['value']);
		
		$this->assign('sms', $sms['value']);
		return $this->fetch();
	}
	
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