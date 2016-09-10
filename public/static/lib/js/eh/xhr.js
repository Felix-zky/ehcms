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
		 * @param  {Mixed}    [done]     请求正确时执行的函数或提示信息
		 * @param  {Mixed}    [fail]     请求错误时执行的函数或提示信息
		 */
		get: function(url, data, dataType, done, fail){
			$.get(url, data, '', dataType || 'json').then(executeDone(done, data), executeFail(fail, data));
		},
		/**
		 * 异步POST请求
		 *
		 * 参数同上
		 */
		post: function(url, data, dataType, done, fail){
			$.post(url, data || {}, $.noop, dataType || 'json').then(
				function(data, sign, xhrObj){
					executeDone(done, data, sign, xhrObj);
				},
				function(xhrObj, sign, statusText){
					executeFail(fail, xhrObj, sign, statusText);
				}
			);
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
	function executeDone(done, data, sign, xhrObj){
		if (!done) {
			dialog.msg(data.msg || '请求成功');
			return false;
		}

		if (data.code == 1) {
			typeof done.success == 'function' ? done.success(data) : dialog.msg(done.success || data.msg, {icon: 6});
		}else {
			typeof done.fail == 'function' ? done.fail(data) : dialog.msg(done.fail || data.msg, {icon: 5});
		}
	}

	/**
	 * 执行失败回调（请求失败，指无法与服务端进行通讯或服务端返回数据异常，并不代表数据处理的失败。）
	 *
	 * @param  {Mixed}  fail 回调函数或函数数组
	 * @param  {Object} data 执行结果
	 */
	function executeFail(fail, xhrObj, sign, statusText){
		//根据服务器返回的状态码，判断错误类型。
		var statusText;

		if (sign == 'parsererror') {
			statusText = '服务端返回数据格式与要求不符！';
		}else{
			switch (xhrObj.status){
				case 404:
					statusText = '服务端页面无法响应！';
					break;
				default:
					statusText = '服务端未知错误！'
			}
		}

		if (!fail) {
			dialog.msg(statusText || '请求失败', {icon: 5});
			return false;
		}else{
			typeof fail == 'function' ? fail(data) : dialog.msg(fail, {icon: 5});
		}
	}

	eh.xhr = xhr;
});