<?php
// +----------------------------------------------------------------------
// | ehcms [ Efficient Handy Content Management System ]
// +----------------------------------------------------------------------
// | Copyright (c) http://ehcms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zky < zky@ehcms.com >
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Login extends Init{
	
	public function __construct(){
		parent::__construct();
	
		$this->view->engine->layout(FALSE);
	}
	
	/**
	 * 管理员登录
	 */
	public function index(){
		return $this->fetch();
	}
	
	/**
	 * 登录验证及跳转
	 */
	public function checkUser(){
        //必须使用post提交
        if (request()->isPost()){
            if (!empty($this->systemSetting['geetest_id']) && !empty($this->systemSetting['geetest_key'])){
                if ($this->geetestValidate(input('geetest_challenge'), input('geetest_validate'), input('geetest_seccode')) !== true) {
                    $this->successResult('验证失败');
                    die;
                }
            }

            $result = action('member/User/login', ['type' => 'admin'], 'event');
            if ($result !== TRUE){
                return $result;
            }else{
                session('eh_admin', 1);
                $this->successResult('S-020101', 'U-010201');
            }
        }else{
            return $this->errorResult('E-020102');
        }
	}

    public function geetest(){
	    if (empty($this->systemSetting['geetest_id']) || empty($this->systemSetting['geetest_key'])){
	        $this->errorResult('当前未设置安全验证，出于安全考虑，请在首次登录后在“系统设置”中进行设置，目前采用<a href="https://www.geetest.com" style="color:#2274ff;" target="_blank">极验验证</a>，请自行前往申请验证账号。');
	        die;
        }

        require_once EXTEND_PATH . 'geetest/lib/class.geetestlib.php';

        $request = request();

        $GtSdk = new \GeetestLib($this->systemSetting['geetest_id'], $this->systemSetting['geetest_key']);
        $data = array(
            "client_type" => "web",
            "ip_address" => $request->ip()
        );

        $status = $GtSdk->pre_process($data, 1);
        session('gtserver', $status);
        $this->successResult($GtSdk->get_response());
    }

    private function geetestValidate($challenge, $validate, $seccode){
        require_once EXTEND_PATH . 'geetest/lib/class.geetestlib.php';

        $request = request();

        $GtSdk = new \GeetestLib($this->systemSetting['geetest_id'], $this->systemSetting['geetest_key']);
        $data = array(
            "client_type" => "web",
            "ip_address" => $request->ip()
        );
        if (session('gtserver') == 1) {   //服务器正常
            $result = $GtSdk->success_validate($challenge, $validate, $seccode, $data);
            if ($result) {
                return true;
            } else {
                return false;
            }
        } else {  //服务器宕机,走failback模式
            if ($GtSdk->fail_validate($challenge, $validate, $seccode)) {
                return true;
            } else {
                return false;
            }
        }
    }
}