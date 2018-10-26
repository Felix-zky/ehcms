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
use think\Route;

Route::domain('admin', function(){
	Route::get('/', 'admin/Index/index');
	
	//注册后台普通路由
	Route::rule('article/resource', 'admin/Article/resource');
	Route::post('Member/list', 'admin/Member/getMemberList');
    Route::post('Article/list', 'admin/Article/getArticleList');
	Route::rule('resource/uploader/[:parentGroupID]/[:childrenGroupID]', 'admin/Resource/uploader', '*', [], ['parentGroupID' => '\d+|all', 'childrenGroupID' => '\d+']);
	Route::get('resource/index/[:group]', 'admin/Resource/index', [], ['group' => '\d+|all']);
	Route::post('resource/addgroup', 'admin/Resource/addGroup');
	Route::delete('resource/deleteresource', 'admin/Resource/deleteResource');
	Route::delete('resource/deleteresources', 'admin/Resource/deleteResources');
	Route::get('resource/iframe', 'admin/Resource/index?iframe=1');
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
	Route::rule('admin_group/getpermission', 'admin/AdminGroup/getPermission');
    Route::rule('cashier_goods/resource', 'admin/CashierGoods/resource');
    Route::post('cashier/getgoods', 'admin/Cashier/getGoods');
    Route::get('cashier/goods', 'admin/Cashier/goods', [], ['id' => '\d+']);
    Route::post('navigation/sort', 'admin/Navigation/sort');
    Route::get('login/geetest', 'admin/Login/geetest');
    Route::put('setting/weixin', 'admin/Setting/weixinUpdate');
    Route::post('cashier/weixin', 'admin/Cashier/weixin');
    Route::post('cashier/weixin_cancel', 'admin/Cashier/weixinCancel');
    Route::post('cashier/weixin_query', 'admin/Cashier/weixinQuery');
    Route::put('setting/alipay', 'admin/Setting/alipayUpdate');
    Route::post('cashier/alipay', 'admin/Cashier/alipay');
    Route::post('admin/register', 'admin/Member/register');
    Route::post('member/sendcode', 'admin/Member/sendCode');
    Route::rule('admin/sign_out', 'admin/Login/signOut');

    ///////////////////////////////////////////////////////////
    Route::rule('store/register', 'admin/MyStore/register', 'get|post');
    Route::post('store/sms', 'admin/MyStore/sms');
    Route::get('store/geetest', 'admin/MyStore/geetest');
    Route::post('store/resource', 'admin/MyStore/resource');
    Route::rule('store/info', 'admin/MyStore/info');
    ///////////////////////////////////////////////////////////
	
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
	Route::resource('member', 'admin/Member');
    Route::resource('cashier_category', 'admin/CashierCategory');
    Route::resource('cashier_goods', 'admin/CashierGoods');
    Route::resource('navigation', 'admin/Navigation');
    Route::resource('my_store_table', 'admin/MyStoreTable');

	Route::rule('login/index', 'admin/Login/index');
});