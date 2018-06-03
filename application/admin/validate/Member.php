<?php
namespace app\admin\validate;

use think\Validate;

class Member extends Validate{
    protected $rule = [
        'username'  =>  'require|only',
        'password' => 'require|min:6',
        'confirm_password' => 'require|confirm:password'
    ];

    protected $message = [
        'username.only' => '用户名重复'
    ];

    protected $scene = [
        'edit' => ['username'],
    ];

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

    private function checkUsername($value){
        $user = db('member')->where('username', $value)->find();
        if ($user){
            return FALSE;
        }else{
            return TRUE;
        }
    }
}