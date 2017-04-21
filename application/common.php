<?php
use think\Route;

//注册后台普通路由
Route::rule('admin/article/resource', 'admin/Article/resource');
Route::post('admin/Member/list', 'admin/Member/getMemberList');
Route::rule('admin/Resource/uploader/[:parentGroupID]/[:childrenGroupID]', 'admin/Resource/uploader', '*', [], ['parentGroupID' => '\d+|all', 'childrenGroupID' => '\d+']);
Route::rule('admin/document/cover', 'admin/Document/uploaderCover');

//注册后台资源路由
Route::resource('admin/article', 'admin/Article');
//Route::resource('admin/article', 'admin/Article');
Route::resource('admin/document', 'admin/Document');
Route::resource('admin/document_category', 'admin/DocumentCategory');