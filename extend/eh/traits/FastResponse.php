<?php
namespace eh\traits;

trait FastResponse{
	
	/**
	 * 自定义快捷成功返回
	 */
	protected function successResult($msg = '成功', $mix = FALSE, $data = [], $wait = 3, $code = 1){
		//如果msg等于字符串类型且通过正则验证，则将返回信息替换，目前正则验证S-开头（区分大小写），后面跟6个数字，且数字结尾。
		if (is_string($msg) && preg_match('/^S-\d{6,8}$/', $msg)){
			$msg = lang($msg);
		}elseif (is_array($msg)){
			if (empty($msg['lang_name'])){
				$this->msgIsData($msg, $data);
			}else{
				$msg = lang($msg['lang_name'], $msg['vars'] || [], $msg['lang'] || '');
			}
		}
		
		//如果$mix为FALSE，则为一般查询数据传递返回，不存在跳转，必存在数据传递。
		//同时也可以减少运算开销。
		if ($mix === FALSE){
			if (request()->isAjax()){
				return $this->result($data, $code, $msg, 'json');
			}else{
				if (is_array($data)){
					foreach ($data as $key=>$value){
						$this->assign($key, $value);
					}
				}
				$msg != '' && $this->assign('ehSuccessMsg', $msg);
				return FALSE;
			}
		}else if (is_string($mix) && preg_match('/^U-\d{6,8}$/', $mix)){
			$mix = eh_url($mix);
		}else if (is_array($mix)){
			//如果为数组则赋值给$data，自身赋值为NULL。
			$this->urlIsData($mix, $data);
		}
		
		//如果请求来自AJAX，那么返回result返回json格式
		//在运行至此位置前，必须将$mix设置为null或者url表达式或者url地址。
		//此返回类型常用于提交、判断、处理后的一些提醒，会带有处理结果或者跳转地址。
		if (request()->isAjax()){
			if ($mix !== FALSE && $mix !== NULL){
				$data['redirect_url'] = preg_match('/^(https?:|\/)/', $mix) ? $mix : url($mix);
			}
			return $this->result($data, $code, $msg, 'json');
		}else{
			return $this->success($msg, $mix ?: NULL, $data, $wait);
		}
	}
	
	/**
	 * 自定义快捷失败返回
	 */
	protected function errorResult($msg = '失败', $mix = FALSE, $data = [], $wait = 3, $code = 0){
		//如果msg等于字符串类型且通过正则验证，则将错误信息以及code替换，目前正则验证E-开头（区分大小写），后面跟6个数字，且数字结尾。
		if (is_string($msg) && preg_match('/^E-\d{6,8}$/', $msg)){
			$code = $msg;
			$msg = lang($msg);
		}elseif (is_array($msg)){
			if (empty($msg['lang_name'])){
				$this->msgIsData($msg, $data);
			}else{
				$code = $msg;
				$msg = lang($msg['lang_name'], $msg['vars'] || [], $msg['lang'] || '');
			}
		}
		
		if ($mix === FALSE){
			if (request()->isAjax()){
				return $this->result($data, $code, $msg, 'json');
			}else{
				if (is_array($data)){
					foreach ($data as $key=>$value){
						$this->assign($key, $value);
					}
				}

				$this->assign('ehErrorMsg', $msg);
				$this->assign('ehErrorCode', $code);
				return FALSE;
			}	
		}else if (preg_match('/^U-\d{6,8}$/', $mix)){
			$mix = eh_url($mix);
		}else if (is_array($mix)){
			//如果为数组则赋值给$data，自身赋值为NULL。
			$this->urlIsData($mix, $data);
		}
		
		if (request()->isAjax()){
			if ($mix !== FALSE && $mix !== NULL){
				$data['redirect_url'] = preg_match('/^(https?:|\/)/', $mix) ? $mix : url($mix);
			}
			return $this->result($data, $code, $msg, 'json');
		}else{
			return $this->error($msg, $mix || NULL, $data, $wait);
		}
	}
	
	/**
	 * 返回数据互换，将引用传入的$msg和$data互换。
	 * 仅在$data为空的情况下执行。
	 *
	 */
	private function msgIsData(&$msg, &$data){
		if (empty($data)){
			$data = $msg;
		}
		$msg = '';
	}
	
	/**
	 * 返回数据互换，将引用传入的$mix和$data互换。
	 * 仅在$data为空的情况下执行。
	 *
	 */
	private function urlIsData(&$mix, &$data){
		if (empty($data)){
			$data = $mix;
		}
		$mix = NULL;
	}
}