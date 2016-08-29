/**
 * XMLHttpRequest-异步请求组件
 *
 * @return {Object} 常用请求合集
 */
define(['layer', 'jquery', 'eh'], function(dialog){
	var xhr = {
		/**
		 * 异步GET请求
		 *
		 * @param  {String}   url        请求URL
		 * @param  {Object}   [data]     请求的参数
		 * @param  {String}   [dataType] 请求返回的类型
		 * @param  {Mixed}    [done]     请求正确时触发的函数或函数组
		 * @param  {Mixed}    [fail]     请求错误时触发的函数或函数组
		 */
		get: function(url, data, dataType, done, fail){
			$.get(url, data, dataType || 'json').then(executeDone(done, data), executeFail(fail, data));
		},
		/**
		 * 异步POST请求
		 *
		 * 参数同上
		 */
		post: function(url, data, dataType, done, fail){
			$.post(url, data, '', "json");
			ceshi();
		},
		/**
		 * 异步PUT请求（配合ThinkPHP的资源路由）
		 *
		 * 参数同上
		 */
		put: function(url, data, dataType, done, fail){
			var option = {
				url: url,
				type: 'put',
				dataType: dataType || 'json',
				data: data
			};

			$.ajax(option).then(executeDone(done, data), executeFail(fail, data));
		},
		/**
		 * 异步DELETE请求（配合ThinkPHP的资源路由）
		 *
		 * 参数同上
		 */
		delete: function(url, data, dataType, done, fail){
			var option = {
				url: url,
				type: 'delete',
				dataType: dataType || 'json',
				data: data
			};

			$.ajax(option).then(executeDone(done, data), executeFail(fail, data));
		},
		/**
		 * 异步跨域请求
		 *
		 * 相同参数同上
		 * @param {String} jsonp         请求参数的数组名称
		 * @param {String} jsonpCallback 请求返回时的函数名称
		 */
		jsonp: function(url, data, dataType, jsonp, jsonpCallback, done, fail){
			var option = {
				url: url,
				type: 'get',
				dataType: dataType || 'json',
				data: data,
				jsonp: jsonp,
				jsonpCallback: jsonpCallback
			};

			$.ajax(option).then(executeDone(done, data), executeFail(fail, data));
		}
	};

	/**
	 * 执行成功回调（请求成功）
	 *
	 * @param  {Mixed}  done 回调函数或函数数组或字符串
	 * @param  {Object} data 执行结果
	 */
	function executeDone(done, data){
		if (typeof done == 'array'){
			//以后添加
		}else if (typeof done == 'function') {
			done(data);
		}else if (typeof done == 'string') {
			if (done == 'return') {
				return data;
			}else{
				dialog.msg(done, {icon: 6});
			}
		}else{
			dialog.msg('请求成功', {icon: 6});
		}
	}

	/**
	 * 执行失败回调（请求失败）
	 *
	 * @param  {Mixed}  fail 回调函数或函数数组
	 * @param  {Object} data 执行结果
	 */
	function executeFail(fail, data){
		if (typeof fail == 'array'){
			//以后添加
		}else if (typeof fail == 'function') {
			fail(data);
		}else{
			dialog.msg('请求失败', {icon: 5});
		}
	}

	eh.xhr = xhr;

	return xhr;
});