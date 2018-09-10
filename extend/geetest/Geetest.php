<?php
namespace geetest;

class Geetest{
    private $geetest_id;
    private $geetest_key;

    public function __construct($geetest_id, $geetest_key){
        $this->geetest_id = $geetest_id;
        $this->geetest_key = $geetest_key;
    }

    public function init(){
        require_once EXTEND_PATH . 'geetest/lib/class.geetestlib.php';

        $request = request();

        $GtSdk = new \GeetestLib($this->geetest_id, $this->geetest_key);
        $data = array(
            "client_type" => "web",
            "ip_address" => $request->ip()
        );

        $status = $GtSdk->pre_process($data, 1);
        session('gtserver', $status);
        return $GtSdk->get_response();
    }

    public function validate($challenge, $validate, $seccode){
        require_once EXTEND_PATH . 'geetest/lib/class.geetestlib.php';

        $request = request();

        $GtSdk = new \GeetestLib($this->geetest_id, $this->geetest_key);
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