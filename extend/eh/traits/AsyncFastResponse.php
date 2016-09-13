<?php
namespace eh\traits;

trait AsyncFastResponse{
	
	/**
	 * 自定义快捷成功返回
	 *
	 * 参数的设置请参照ThinkPHP Jump->result()
	 */
	protected function ajaxSuccessResult($msg = '成功', $data = '', $code = 1){
		$this->ajaxResultMsgIsData($msg, $data);
		return $this->result($data, $code, $msg, 'json');
	}
	
	/**
	 * 自定义快捷失败返回
	 *
	 * 参数的设置请参照ThinkPHP Jump->result()
	 */
	protected function ajaxErrorResult($msg = '失败', $code = 0, $data = ''){
		$this->ajaxResultMsgIsData($msg, $data);
		return $this->result($data, $code, $msg, 'json');
	}
	
	/**
	 * 异步返回数据互换，目的为了在使用异步返回时调用方便一些，在满足以下3个条件的情况下，识别为传入的是data数据，将引用传入的msg和data互换。
	 *
	 * 条件：
	 * 1、$msg为数组;
	 * 2、$msg存在键名为isData且键值为TRUE的属性;
	 * 3、$data为空;
	 *
	 * 理论上$msg（提示信息）一般为字符串类型，当它为数组时一定是data了，但为了保证程序的稳定性，将条件增加到3条，自由度也得到了提升，是否互换由使用者来决定。
	 */
	private function ajaxResultMsgIsData(&$msg, &$data){
		if (is_array($msg) && !empty($msg['isData']) && $msg['isData'] === TRUE && empty($data)){
			unset($msg['isData']);
			$data = $msg;
			$msg = '';
		}
	}
	
}