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

namespace eh;

use think\Db;
class Resource{
	private $path;
	private $rule;
	private $error;
	private $table;
	private $ids = [];
	private $data = [];
	
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
			
			if (is_file($this->path.DS.$savename.DS.'.'.$result['extension'])){
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
					'original_name' => strstr($file->getInfo('name'), '.', true),
					'name' => strstr($uploader->getFilename(), '.', true),
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
				
				$this->data = $data;
				$resourceID = db($this->table)->insertGetId($data);
				if ($resourceID > 0){
					return $resourceID;
				}else{
					$this->error = '资源存入失败';
					$uploader = null;
					@unlink($pathName);
					return false;
				}
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
	 * @return boolean
	 */
	public function delete($id){
		if (is_numeric($id)){
			$result = Db::name($this->table)->where(['uid' => cookie('user_id'), 'id' => $id])->find();
			
			if (!$result){
				$this->error = '资源不存在';
				return FALSE;
			}
			
			if (Db::name($this->table)->delete($result['id']) == 1){
				@unlink($result['path'].DS.$result['name'].'.'.$result['extension']);
				return TRUE;
			}else{
				$this->error = '资源数据无法删除';
				return FALSE;
			}
		}elseif (is_array($id)){
			$result = Db::name($this->table)->where(['uid' => cookie('user_id'), 'id' => ['in', $id]])->select();
			if (is_array($result)){
				foreach ($result as $v){
					if (Db::name($this->table)->delete($v['id']) == 1){
						$this->ids[] = $v['id'];
						@unlink($v['path'].DS.$v['name'].'.'.$v['extension']);
					}else{
						$this->error = '处理'.$v['original_name'].'时出错，终止执行！';
						return false;
					}
				}
				return TRUE;
			}else{
				$this->error = '资源无法获取';
				return FALSE;
			}
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
	 * 获取已执行的ID编号
	 */
	public function getIds(){
		return $this->ids;
	}
	
	/**
	 * 获取执行的数据，目前只有在新增和更新后，可以获取到
	 * @access public
	 * @param string $key 获取数据中指定的键值。
	 */
	public function getData($key = ''){
		if (!empty($key) && in_array($key, $this->data)){
			return $this->data[$key];
		}else{
			return $this->data;
		}
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