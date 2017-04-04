<?php
namespace eh;

use think\Db;
class Resource{
	private $path;
	private $rule;
	public $error;
	
	public function __construct($path = '', $rule = 'date'){
		$this->path = $path ?: 'uploader/'.cookie('user_id');
		$this->rule = $rule;
	}
	
	/**
	 * 执行上传操作
	 * @access public
	 * @param object $file 上传资源对象
	 * @param array $validate 资源验证条件
	 * @param boolean $temporary 是否存入临时资源库，默认直接存入正式资源库。
	 * @param string|boolean $savename 是否使用原资源名，默认自动生成，传入false使用资源原名，传入字符串使用指定资源名。
	 * @param int|false $replace 是否替换指定资源，默认不覆盖。
	 * @return boolean
	 */
	public function uploader($file, $validate = [], $temporary = FALSE, $savename = TRUE, $replace = FALSE)
	{
		if (is_numeric($replace)){
			$result = db('resource')->where(['id' => $replace, 'uid' => cookie('user_id')])->find();
			if (!$result){
				$this->error = '替换资源不存在';
				return false;
			}
			
			$this->path = $result['path'];
			$savename = $result['name'];
			
			if (is_file($this->path.'/'.$savename.'.'.$result['extension'])){
				$uploader = $file->validate($validate)->move($this->path, $savename, TRUE);
				if ($uploader){
					return true;
				}else{
					$this->error = $uploader->getError();
					return false;
				}
			}else{
				$this->error = '替换资源路径不符';
				return false;
			}
		}else{
			$uploader = $file->validate($validate)->rule($this->rule)->move($this->path, $savename, $replace);
			if ($uploader){
				print_r($uploader);
				return true;
			}else{
				$this->error = $file->getError();
				return false;
			}
		}
	}
	
}