<?php
namespace app\member\event;

use app\common\controller\Base;
class User extends Base{
	use \eh\traits\Password;
	
	/**
	 * 检查用户登录状态
	 */
	public function checkUserLogin(){
		if (!cookie('?user_id') || !cookie('?user_sign')){
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * 验证用户账号密码并创建用户cookie
	 * 
	 * @return TRUE or 错误信息 
	 */
	public function login($type = ''){
		$post = input('post.');
			
		//未填写用户名或密码
		if (!$post['username'] || !$post['password']){
			return $this->errorResult('E-020101');
		}
			
		$member = db('member')->where('username', $post['username'])->find();
		
		if ($type == 'admin' && $member['is_admin'] != 1){
			return $this->errorResult('管理员账号不存在');
		}
			
		//根据用户查询，无法查找到用户
		if (!$member || !is_array($member)){
			return $this->errorResult('E-020103');
		}
			
		//密码验证不通过
		if (!$this->checkPassword($post['password'], $member['password'])){
			return $this->errorResult('E-020104');
		}

        $this->generateLoginStatus($member);
		
		return TRUE;
	}
	
	/**
	 * 获取指定用户信息
	 */
	public function getSingleUser($where, $field = ''){
		if (empty($where) || !is_array($where)){
			return false;
		}
	
		return db('member')->field($field)->where($where)->find();
	}

    /**
     * 生成登录状态
     */
	public function generateLoginStatus($member){
        cookie('user_id', $member['id']);
        cookie('user_sign', md5($member['id'].$member['username'].$member['password'].'eh'.$member['create_time']));
    }

    /**
     * 退出
     */
    public function signOut(){
        cookie('user_id', null);
        cookie('user_sign', null);
    }
}