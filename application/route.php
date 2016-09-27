<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__rest__' => [
    	'admin/Article'    => 'admin/Article',
    	'admin/Authorize'  => 'admin/Authorize',
    	'admin/Department' => 'admin/Department',
    	'admin/Member'     => 'admin/Member',
    	'admin/Setting'    => 'admin/Setting'
    ],
    'admin/Member/index/:pages' => ['admin/Member/index', ['method' => 'get'], ['pages' => '\d+']]
];
