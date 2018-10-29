<?php
namespace app\admin\validate;
use think\Validate;

/**
 * 用户验证器
 */
class Member extends Validate{
    protected $rule = [
        'username'  =>  'require|only',
        'password' => 'require|min:6',
        'confirm_password' => 'require|confirm:password',
        'phone' => 'require|length:11|phoneOnly',
    ];

    protected $message = [
        'username.only' => '用户名重复',
        'code.require' => '验证码必须设置',
        'code.codeCheck' => '验证码验证失败，请重试',
        'phone.require' => '手机号必须设置',
        'phone.phoneOnly' => '手机号已存在，请更换'
    ];

    protected $scene = [
        'edit' => ['username'], //编辑场景
        'admin_register' => ['username', 'password', 'phone', 'code'] //管理后台注册场景
    ];

    /**
     * 验证码校验
     * @return bool
     */
    protected function codeCheck($value, $rule, $data){
        $res = db('verification_code')->where(['code' => $value, 'phone' => $data['phone'], 'scene' => 'member-register'])->find();
        if ($res){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * 用户名唯一校验
     * @return bool
     */
    protected function only($value, $rule, $data){
        if (!empty($data['id'])){
            $user = db('member')->where('id', $data['id'])->find();
            if ($user['username'] == $value){
                return TRUE;
            }else{
                return $this->checkUsername($value);
            }
        }else{
            return $this->checkUsername($value);
        }
    }

    /**
     * 手机号唯一校验
     * @return bool
     */
    protected function phoneOnly($value){
        $res = db('member')->where('phone', $value)->find();
        if ($res){
            return FALSE;
        }else{
            return TRUE;
        }
    }

    /**
     * 查询用户名是否存在
     * @access private
     * @return bool
     */
    private function checkUsername($value){
        $user = db('member')->where('username', $value)->find();
        if ($user){
            return FALSE;
        }else{
            return TRUE;
        }
    }
}