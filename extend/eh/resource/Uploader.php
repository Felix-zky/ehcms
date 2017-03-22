<?php
namespace eh\resource;

use think\Db;
abstract class Uploader{
	const TYPE_IMAGE = 1;
	
	protected $typeReg;
	
	protected function uploader()
	{
		
	}
	
	protected function parseType($fileType)
	{
		$result = preg_match($this->typeReg, $fileType);
		
		if (!$result) {
			
		}
	}
}