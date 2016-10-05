<?php
namespace app\common\controller;

use think\Controller;
use eh\EhUrl;
class Base extends Controller{
	//加载异步快速反馈功能
	use \eh\traits\FastResponse;
	//加载函数库
	use \eh\traits\FunctionLibrary;
	
	public function __construct(){
		parent::__construct();
		
		//加载URL地址库
		EhUrl::load(EXTEND_PATH . 'eh/EhUrl/config.php');
	}
}