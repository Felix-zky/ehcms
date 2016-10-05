<?php
namespace eh\traits;

trait FunctionLibrary{
	
	/**
	 * 异步POST双重检查，一般用于API请求。
	 * @return boolean or object
	 */
	protected function asyncPostCheck(){
		if (!request()->isAjax()){
			return $this->errorResult('E-010101');
		}
		
		if (!request()->isPost()){
			return $this->errorResult('E-010102');
		}
		
		return TRUE;
	}
	
}