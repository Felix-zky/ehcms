<?php
use think\Route;

//注册后台普通路由
Route::rule('admin/article/resource', 'admin/Article/resource');
Route::post('admin/Member/list', 'admin/Member/getMemberList');
Route::rule('admin/Resource/uploader/[:parentGroupID]/[:childrenGroupID]', 'admin/Resource/uploader', '*', [], ['parentGroupID' => '\d+|all', 'childrenGroupID' => '\d+']);
Route::rule('admin/document/cover', 'admin/Document/uploaderCover');
Route::rule('admin/article_category/parent_id/:parent_id', 'admin/ArticleCategory/index', 'get', [], ['parent' => '\d+']);
Route::rule('admin/document_category/parent_id/:parent_id', 'admin/DocumentCategory/index', 'get', [], ['parent' => '\d+']);
Route::get('admin/article/getcategory', 'admin/Article/getCategory');
Route::get('admin/document/getcategory', 'admin/Document/getCategory');

//注册后台资源路由
Route::resource('admin/article', 'admin/Article');
Route::resource('admin/article_category', 'admin/ArticleCategory');
Route::resource('admin/document', 'admin/Document');
Route::resource('admin/document_category', 'admin/DocumentCategory');
Route::resource('admin/goods', 'admin/Goods');
Route::resource('admin/goods_category', 'admin/GoodsCategory');