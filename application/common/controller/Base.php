<?php
namespace app\common\controller;

use think\Controller;
use think\Lang;
class Base extends Controller{
	use \eh\traits\AsyncFastResponse;
	
	public function __construct(){
		parent::__construct();
		
		//先固定使用zh-cn，后期改多语言。
		Lang::load(ROOT_PATH . 'lang/zh-cn/error.php');
		Lang::load(APP_PATH . 'lang/zh-cn/success.php');
	}
}