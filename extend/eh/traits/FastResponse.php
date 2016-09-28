<?php
namespace eh\traits;

trait FastResponse{
	
	/**
	 * 自定义快捷成功返回
	 */
	protected function successResult($msg = '成功', $mix = null, $data = '', $wait = 3, $code = 1){
		//如果msg等于字符串类型且通过正则验证，则将返回信息替换，目前正则验证S-开头（区分大小写），后面跟6个数字，且数字结尾。
		if (is_string($msg) && preg_match('/^S-\d{6}$/', $msg)){
			$msg = lang($msg);
		}else{
			$this->msgIsData($msg, $data);
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
				$msg != '' && $this->assign('eh_success_msg', $msg); 
				return FALSE;
			}
		//$mix不等于null的情况下才对其进行解析。
		}else if ($mix != null){
			//如果为数组则赋值给$data，自身赋值为null。
			if (is_array($mix)){
				$this->urlIsData($mix, $data);
			}else if (preg_match('/^U-\d{6}$/', $mix)){
				$mix = eh_url($mix);
			}
		}
		
		//如果请求来自AJAX，那么返回result返回json格式
		//在运行至此位置前，必须将$mix设置为null或者url表达式或者url地址。
		//此返回类型常用于提交、判断、处理后的一些提醒，会带有处理结果或者跳转地址。
		if (request()->isAjax()){
			if ($mix != null){
				$data['redirect_url'] = preg_match('/^(https?:|\/)/', $mix) ? $mix : url($mix);
			}
			return $this->result($data, $code, $msg, 'json');
		}else{
			return $this->success($msg, $mix, $data, $wait);
		}
	}
	
	/**
	 * 自定义快捷失败返回
	 */
	protected function errorResult($msg = '失败', $mix = null, $data = '', $wait = 3, $code = 0){
		//如果msg等于字符串类型且通过正则验证，则将错误信息以及code替换，目前正则验证E-开头（区分大小写），后面跟6个数字，且数字结尾。
		if (is_string($msg) && preg_match('/^E-\d{6}$/', $msg)){
			$code = $msg;
			$msg = lang($msg);
		}else{
			$this->msgIsData($msg, $data);
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

				$this->assign('eh_error_msg', $msg);
				$this->assign('eh_error_code', $code);
				return FALSE;
			}	
		}else if ($mix != null){
			if (is_array($mix)){
				$this->urlIsData($mix, $data);
			}else if (preg_match('/^U-\d{6}$/', $mix)){
				$mix = eh_url($mix);
			}
		}
		
		if (request()->isAjax()){
			if ($mix != null){
				$data['redirect_url'] = preg_match('/^(https?:|\/)/', $mix) ? $mix : url($mix);
			}
			return $this->result($data, $code, $msg, 'json');
		}else{
			return $this->error($msg, $mix, $data, $wait);
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
	private function msgIsData(&$msg, &$data){
		if (is_array($msg) && empty($msg['isMsg']) && empty($data)){
			unset($msg['isMsg']);
			$data = $msg;
			$msg = '';
		}
	}
	
	private function urlIsData(&$mix, &$data){
		if (empty($data)){
			$data = $mix;
		}
		$mix = null;
	}
}