<?php
namespace app\admin\controller;

class SettingSms extends Init{
	
	public function index(){
		return $this->fetch();
	}
	
	public function update(){
		if (request()->isPut()){
			print_r(input('param.'));
			die;
				
			$data = [
				'name' => '阿里云短信接口配置',
				'key' => 'aliyun_sms',
				'data' => serialize(input('param.'))
			];
				
			$result = db('admin_setting')->where('key', 'aliyun_sms')->update($data);
				
			if ($result != 1){
				echo 123;
				die;
			}
		}
	}
}