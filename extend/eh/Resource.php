<?php
namespace eh;

class Resource{
	private $path;
	private $rule;
	private $error;
	private $table;
	
	public function __construct($temporary = FALSE, $path = '', $rule = 'date')
	{
		$this->path = $path ?: 'uploader'.DS.cookie('user_id');
		$this->rule = $rule;
		$this->table = $temporary === FALSE ? 'resource' : 'resource_transfer';
	}
	
	/**
	 * 执行上传操作
	 * @access public
	 * @param object $file 上传资源对象
	 * @param int $groupID 资源分组ID，默认是未分组。
	 * @param array $validate 资源验证条件
	 * @param boolean $temporary 是否存入临时资源库，默认直接存入正式资源库。
	 * @param string|boolean $savename 是否使用原资源名，默认自动生成，传入false使用资源原名，传入字符串使用指定资源名。
	 * @param int|false $replace 是否替换指定资源，默认不覆盖。
	 * @return boolean
	 */
	public function uploader($file, $groupID = 0, $validate = [], $savename = TRUE, $replace = FALSE)
	{
		if (is_numeric($replace)){
			$result = db('resource')->where(['id' => $replace, 'uid' => cookie('user_id')])->find();
			if (!$result){
				$this->error = '替换资源不存在';
				return false;
			}
			
			$this->path = $result['path'];
			$savename = $result['name'];
			
			if (is_file($this->path.DS.$savename)){
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
			//检查当前分组是否为当前用户所有
			if ($groupID != 0 && !db('resource_group')->where(['uid' => cookie('user_id'), 'id' => $groupID])->find()){
				$this->error = "资源分组不可用";
				return false;
			}
			
			$uploader = $file->validate($validate)->rule($this->rule)->move($this->path, $savename, $replace);
			if ($uploader){
				$extension = $uploader->getExtension();
				$pathName = $uploader->getPathname();
				$type = $this->parseType($extension);
				
				$data = [
					'uid' => cookie('user_id'),
					'group_id' => $groupID,
					'original_name' => $file->getInfo('name'),
					'name' => $uploader->getFilename(),
					'path' => $uploader->getPath(),
					'url' => '/'.str_replace('\\', '/', $pathName),
 					'extension' => $uploader->getExtension(),
					'type' => $type,
					'size' => $uploader->getSize(),
					'create_time' => (int)THINK_START_TIME
				];
				
				if ($type == 1){
					list($width, $height) = getimagesize($pathName);
					$data['width'] = $width;
					$data['height'] = $height;
				}
				
				if (db($this->table)->insert($data) != 1){
					$this->error = '资源存入失败';
					$uploader = null;
					@unlink($pathName);
					return false;
				}
				
				return true;
			}else{
				$this->error = $file->getError();
				return false;
			}
		}
	}
	
	/**
	 * 删除资源
	 * @access public
	 * @param int|array $id 资源的id编号
	 * @param boolean $temporary 是否为临时资源库
	 */
	public function delete($id, $temporary = FALSE){
		
		if (is_numeric($id)){
			if (db('resource')->where('uid',cookie('user_id'))->delete($resourceID) == 1){
				return TRUE;
			}
		}elseif (is_array($id)){
			
		}
	}
	
	/**
	 * 获取错误信息
	 * @return string
	 */
	public function getError()
	{
		return $this->error;
	}
	
	/**
	 * 解析后缀名，分配类型编号。
	 * @param string $extension 文件后缀名
	 * @return number
	 */
	private function parseType($extension)
	{
		$type = [
			1 => ['jpg', 'gif', 'jpeg', 'png', 'ico'],
			2 => ['rar', 'zip', 'gzip', 'tar'],
			3 => ['mp3'],
			4 => ['avi', 'wma', 'rmvb', 'mp4', '3gp'],
			5 => ['txt', 'xlsx', 'xls', 'pdf', 'doc', 'docx', 'ppt'],
			6 => ['html', 'htm', 'js', 'css', 'less', 'sass', 'php', 'asp', 'aspx'],
			7 => ['exe', 'apk', 'ipa']
		];
		
		foreach ($type as $k=>$v){
			if (in_array($extension, $v)){
				return $k;
			}
		}
		
		return 0;
	}
}