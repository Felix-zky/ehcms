<?php
namespace sms;

use \Dysmsapi\Request\V20170525 as SMS;
class Init{
	private $client;
	private $request;
	private $error = '';
	
	public function __construct($code, $phone, $config, $param = ''){
		include_once 'aliyun-php-sdk-core/Config.php';
		
		if (empty($code) || empty($phone) || !is_array($config) || empty($config['region']) || empty($config['access_key_id']) || empty($config['access_key_secret']) || empty($config['sign'])){
			$this->error = "初始化错误";
		}
		
		$profile = \DefaultProfile::getProfile($config['region'], $config['access_key_id'], $config['access_key_secret']);
		\DefaultProfile::addEndpoint($config['region'], $config['region'], 'Dysmsapi', 'dysmsapi.aliyuncs.com');
		$this->client = new \DefaultAcsClient($profile);
		
		$this->request = new SMS\SendSmsRequest;
		//必填-短信接收号码
		$this->request->setPhoneNumbers($phone);
		//必填-短信签名
		$this->request->setSignName($config['sign']);
		//必填-短信模板Code
		$this->request->setTemplateCode($code);
		//选填-假如模板中存在变量需要替换则为必填(JSON格式)
		$param && $this->request->setTemplateParam($param);
		//选填-发送短信流水号
		$config['out_id'] && $this->request->setOutId($config['out_id']);
	}
	
	public function send(){
		if ($this->error != ''){
			return false;
		}
		
		try {
			$response = $this->client->getAcsResponse($this->request);
			return true;
		}
		catch (ClientException  $e) {
			return false;
		}
		catch (ServerException  $e) {
			return false;
		}
	}
}