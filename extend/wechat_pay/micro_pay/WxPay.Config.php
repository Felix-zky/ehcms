<?php

class WxPayConfig extends WxPayConfigInterface
{
    private $setting;

    public function __construct()
    {
        $pay = db('admin_setting')->where('key', 'weixin_pay')->find();
        $this->setting = unserialize($pay['value']);
    }

    public function GetAppId()
	{
		return $this->setting['app_id'];
	}

	public function GetMerchantId()
	{
		return $this->setting['mch_id'];
	}

	public function GetNotifyUrl()
	{
		return "";
	}
	public function GetSignType()
	{
		return "HMAC-SHA256";
	}

	public function GetProxy(&$proxyHost, &$proxyPort)
	{
		$proxyHost = "0.0.0.0";
		$proxyPort = 0;
	}

	public function GetReportLevenl()
	{
		return 1;
	}

	public function GetKey()
	{
		return $this->setting['key'];
	}
	public function GetAppSecret()
	{
		return '';
	}

	public function GetSSLCertPath(&$sslCertPath, &$sslKeyPath)
	{
		$sslCertPath = EXTEND_PATH . 'wechat_pay/micro_pay/cert/apiclient_cert.pem';
		$sslKeyPath = EXTEND_PATH . 'wechat_pay/micro_pay/cert/apiclient_key.pem';
	}
}
