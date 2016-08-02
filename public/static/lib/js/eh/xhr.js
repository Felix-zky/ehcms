define(['layer', 'jquery', 'eh'], function(dialog){
	var xhr = {
		/**
		 * 异步GET请求
		 *
		 * @param  {String}   url      [description]
		 * @param  {Object}   data     [description]
		 * @param  {String}   dataType [description]
		 * @param  {Mixed}    done     [description]
		 * @param  {[type]}   fail     [description]
		 * @return {[type]}            [description]
		 */
		get: function(url, data, dataType, done, fail){
			$.get(url, data, dataType || 'json').then(executeDone(done, data), executeFail(fail, data));
		},
		post: function(url, data, dataType, done, fail){
			$.post(url, data, dataType || 'json').then(executeDone(done, data), executeFail(fail, data));
		},
		put: function(url, data, dataType, done, fail){
			var option = {
				url: url,
				type: 'put',
				dataType: dataType || 'json',
				data: data
			};

			$.ajax(option).then(executeDone(done, data), executeFail(fail, data));
		},
		delete: function(url, data, dataType, done, fail){
			var option = {
				url: url,
				type: 'delete',
				dataType: dataType || 'json',
				data: data
			};

			$.ajax(option).then(executeDone(done, data), executeFail(fail, data));
		},
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
			dialog.msg(done, {icon: 6});
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