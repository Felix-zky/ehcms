<?php
namespace eh\traits;

trait FastResponse{
	
	/**
	 * 自定义快捷成功返回
	 *
	 * 参数的设置请参照ThinkPHP Jump->result()
	 */
	protected function successResult($msg = '成功', $data = '', $url = null, $wait = 3, $code = 1){
		//如果msg等于字符串类型且通过正则验证，则将返回信息替换，目前正则验证S-开头（区分大小写），后面跟6个数字，且数字结尾。
		if (is_string($msg) && preg_match('/^S-\d{6}$/', $msg)){
			$msg = lang($msg);
		}else{
			$this->ResultMsgIsData($msg, $data);
		}
		
		//如果请求来自AJAX，那么返回result返回json格式，并且判断url是否存在，不存在则不传递。
		if (request()->isAjax()){
			if ($url != null){
				$data['redirect_url'] = $url;
				$data['redirect_wait'] = $wait;
			}
			return $this->result($data, $code, $msg, 'json');
		}else{
			return $this->success($msg, $url, $data, $wait);
		}
	}
	
	/**
	 * 自定义快捷失败返回
	 *
	 * 参数的设置请参照ThinkPHP Jump->result()
	 */
	protected function errorResult($msg = '失败', $data = '', $url = null, $wait = 3, $code = 0){
		//如果msg等于字符串类型且通过正则验证，则将错误信息以及code替换，目前正则验证E-开头（区分大小写），后面跟6个数字，且数字结尾。
		if (is_string($msg) && preg_match('/^E-\d{6}$/', $msg)){
			$code = $msg;
			$msg = lang($msg);
		}else{
			$this->ResultMsgIsData($msg, $data);
		}
		
		if (request()->isAjax()){
			if ($url != null){
				$data['redirect_url'] = $url;
				$data['redirect_wait'] = $wait;
			}
			return $this->result($data, $code, $msg, 'json');
		}else{
			return $this->error($msg, $url, $data, $wait);
		}
	}
	
	/**
	 * 异步返回数据互换，目的为了在使用异步返回时调用方便一些，在满足以下3个条件的情况下，识别为传入的是data数据，将引用传入的msg和data互换。
	 *
	 * 条件：
	 * 1、$msg为数组;
	 * 2、$msg数组中不存在isMsg键名（为isMsg赋任何empty返回false的值都将导致不做任何处理）;
	 * 3、$data为空;
	 *
	 * 理论上$msg（提示信息）一般为字符串类型，当它为数组时一定是data了，但为了保证程序的稳定性，将条件增加到3条，自由度也得到了提升，是否互换由使用者来决定。
	 */
	private function ResultMsgIsData(&$msg, &$data){
		if (is_array($msg) && empty($msg['isMsg']) && empty($data)){
			unset($msg['isData']);
			$data = $msg;
			$msg = '';
		}
	}
}