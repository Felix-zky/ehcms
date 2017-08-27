<?php
use think\Route;

Route::domain('admin', function(){
	Route::get('/', 'admin/Index/index');
	
	//注册后台普通路由
	Route::rule('article/resource', 'admin/Article/resource');
	Route::post('Member/list', 'admin/Member/getMemberList');
	Route::rule('resource/uploader/[:parentGroupID]/[:childrenGroupID]', 'admin/Resource/uploader', '*', [], ['parentGroupID' => '\d+|all', 'childrenGroupID' => '\d+']);
	Route::get('resource/index/[:group]', 'admin/Resource/index', [], ['group' => '\d+|all']);
	Route::post('/resource/addgroup', 'admin/Resource/addGroup');
	Route::delete('/resource/deleteresource', 'admin/Resource/deleteResource');
	Route::delete('/resource/deleteresources', 'admin/Resource/deleteResources');
	Route::rule('document/cover', 'admin/Document/uploaderCover');
	Route::rule('article_category/parent_id/:parent_id', 'admin/ArticleCategory/index', 'get', [], ['parent' => '\d+']);
	Route::rule('document_category/parent_id/:parent_id', 'admin/DocumentCategory/index', 'get', [], ['parent' => '\d+']);
	Route::rule('goods_category/parent_id/:parent_id', 'admin/GoodsCategory/index', 'get', [], ['parent' => '\d+']);
	Route::get('article/getcategory', 'admin/Article/getCategory');
	Route::get('document/getcategory', 'admin/Document/getCategory');
	Route::get('goods/getcategory', 'admin/Goods/getCategory');
	Route::rule('goods/resource', 'admin/Goods/resource');
	Route::put('setting_sms/update', 'admin/SettingSms/update');
	Route::get('member_point/getmember', 'admin/MemberPoint/getMember');
	Route::rule('member_point/edit', 'admin/MemberPoint/edit');
	Route::rule('member_point/bind', 'admin/MemberPoint/bind');
	Route::rule('order/point', 'admin/Order/point');
	Route::post('permission/getgroup', 'admin/Permission/getGroup');
	
	
	//注册后台资源路由
	Route::resource('article', 'admin/Article');
	Route::resource('article_category', 'admin/ArticleCategory');
	Route::resource('document', 'admin/Document');
	Route::resource('document_category', 'admin/DocumentCategory');
	Route::resource('goods', 'admin/Goods');
	Route::resource('goods_category', 'admin/GoodsCategory');
	Route::resource('module', 'admin/Module');
	Route::resource('permission', 'admin/Permission');
	Route::resource('permission_group', 'admin/PermissionGroup');
	Route::resource('admin_group', 'admin/AdminGroup');
	
	Route::rule('login/index', 'admin/Login/index');
});