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

    protected function only($value){
        $user = db('member')->where('username', $value)->find();
        if ($user){
            return FALSE;
        }else{
            return TRUE;
        }
    }
}