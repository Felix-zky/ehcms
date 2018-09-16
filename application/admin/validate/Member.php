<?php
namespace app\admin\validate;

use think\Validate;

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
        'edit' => ['username'],
        'admin_register' => ['username', 'password', 'phone', 'code']
    ];

    protected function codeCheck($value, $rule, $data){
        $res = db('verification_code')->where(['code' => $value, 'phone' => $data['phone'], 'scene' => 'member-register'])->find();
        if ($res){
            return TRUE;
        }else{
            return FALSE;
        }
    }

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

    protected function phoneOnly($value){
        $res = db('member')->where('phone', $value)->find();
        if ($res){
            return FALSE;
        }else{
            return TRUE;
        }
    }

    private function checkUsername($value){
        $user = db('member')->where('username', $value)->find();
        if ($user){
            return FALSE;
        }else{
            return TRUE;
        }
    }
}