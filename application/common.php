<?php
use think\Route;

// 应用公共文件
Route::post('admin/Member/list' ,'admin/Member/getMemberList');
Route::rule('admin/Resource/uploader/[:parentGroupID]/[:childrenGroupID]', 'admin/Resource/uploader', '*', [], ['parentGroupID' => '\d+|all', 'childrenGroupID' => '\d+']);