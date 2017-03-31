/**
 * XMLHttpRequest-异步请求组件
 */
define(['jquery', 'laytpl', 'layer', 'eh'], function($, laytpl){
	var xhr = {
		/** 数据返回成功处理方式标识 */
		doneState:{
			'message': 1, //仅提示信息
			'messageRedirect': 2, //提示信息并跳转（有返回跳转地址的情况下）
			'messageRefresh': 3, //提示信息并刷新页面。
		},
		/** 用于关闭弹窗 */
		layerIndex: null,
		msgLayerIndex: null,

		/**
		 * 异步GET请求
		 *
		 * @param  {String}   url        请求URL
		 * @param  {Object}   [data]     请求的参数
		 * @param  {String}   [dataType] 请求返回的类型
		 * @param  {Mixed}    [done]     请求正确时执行的函数或提示信息
		 * @param  {Mixed}    [fail]     请求错误时执行的函数或提示信息
		 */
		get: function(url, data, done, fail, dataType){
			$.get(url, data, $.noop, dataType || 'json').then(
				function(data, sign, xhrObj){
					executeDone(done, data, sign, xhrObj);
				},
				function(xhrObj, sign, statusText){
					executeFail(fail, xhrObj, sign);
				}
			);
		},

		/**
		 * 异步POST请求
		 *
		 * 参数同上
		 */
		post: function(url, data, done, fail, dataType){
			$.post(url, data || {}, $.noop, dataType || 'json').then(
				function(data, sign, xhrObj){
					executeDone(done, data, sign, xhrObj);
				},
				function(xhrObj, sign, statusText){
					executeFail(fail, xhrObj, sign);
				}
			);
		},

		/**
		 * 异步PUT请求（主要配合ThinkPHP的资源路由）
		 *
		 * 参数同上
		 */
		put: function(url, data, done, fail, dataType){
			var option = {
				url: url,
				type: 'put',
				dataType: dataType || 'json',
				data: data
			};

			$.ajax(option).then(
				function(data, sign, xhrObj){
					executeDone(done, data, sign, xhrObj);
				},
				function(xhrObj, sign, statusText){
					executeFail(fail, xhrObj, sign);
				}
			);
		},

		/**
		 * 异步DELETE请求（主要配合ThinkPHP的资源路由）
		 *
		 * 参数同上
		 */
		delete: function(url, data, done, fail, dataType){
			var option = {
				url: url,
				type: 'delete',
				dataType: dataType || 'json',
				data: data
			};

			$.ajax(option).then(
				function(data, sign, xhrObj){
					executeDone(done, data, sign, xhrObj);
				},
				function(xhrObj, sign, statusText){
					executeFail(fail, xhrObj, sign);
				}
			);
		},

		/**
		 * 异步跨域请求
		 *
		 * 相同参数同上
		 * @param {String} jsonp         请求参数的名称
		 * @param {String} jsonpCallback 请求返回时的函数名称
		 */
		jsonp: function(url, data, done, fail, jsonp, jsonpCallback, dataType){
			var option = {
				url: url,
				type: 'get',
				dataType: 'jsonp',
				data: data,
				jsonp: jsonp || '',
				jsonpCallback: jsonpCallback || ''
			};

			$.ajax(option).then(
				function(data, sign, xhrObj){
					executeDone(done, data, sign, xhrObj);
				},
				function(xhrObj, sign, statusText){
					executeFail(fail, xhrObj, sign);
				}
			);
		},

		/**
		 * get快捷方式 常规通用
		 * @param {String} url 请求地址
		 * @param {Object} [varname] [description]
		 */
		getCommon: function(url, msg, data, tplID, viewObj, func){
			var option = {};

			if (typeof data == 'object' && data.page){
				option.page = data.page;
			}

			if (msg == 'get' || msg == 'getList') {
				option.type = msg;
			}else if (!!msg) {
				option.str = msg;
			}else{
				option.type = 'get';
			}

			if (typeof viewObj == 'string') {
				viewObj = $(viewObj);
			}

			xhr.msgLayerIndex || this.loadPrompt(option);

			var success = function(response){
				func = func || {};

				if (typeof func == 'function') {
					func(parentObj, json, response);
				}else{
					if (typeof func.before == 'function') {
						var res = func.before(response);

						if (res === false) {
							return false;
						}
					}

					if (func.render !== false) {
						laytpl($('#' + tplID).html()).render(response.data, function(html){
							viewObj.html(html);
						});
					}

					if (typeof func.after == 'function') {
						func.after(response);
					}
				}

				xhr.msgLayerIndex && layerClose(xhr.msgLayerIndex);
				xhr.msgLayerIndex = null;
			};

			this.get(url, data, {'success': success});
		},

		/**
		 * 更新后替换内容，更新成功后对根本父元素查找对应的子元素并替换其内容，可以减少服务端请求数及传输量。
		 * 目前仅使用常规（替换html内容）方法进行处理，如有特殊情况传入func参数即可。
		 * 
		 * @param {String}           url        请求地址
		 * @param {String}           data       请求数据（URL参数）
		 * @param {Object}           json       请求数据（json对象）
		 * @param {String or Object} parentObj  父元素的ID或者父元素的对象
		 * @param {Number}           layerIndex 需要关闭的编辑页面
		 * @param {Function}         [func]     数据自定义处理方法
		 */
		putCommon: function(url, data, json, parentObj, dataTag, layerIndex, func){
			layerIndex && setLayerClose(layerIndex);
			xhr.msgLayerIndex || this.loadPrompt();

			if (typeof parentObj == 'string') {
				parentObj = $(parentObj);
			}

			var success = function(response){
				layerClose(this.msgLayerIndex);

				xhr.layerIndex && layerClose(xhr.layerIndex);
				xhr.layerIndex = null;

				layer.msg(response.msg || '更新成功！', {icon: 6});

				if (typeof func == 'function') {
					func(parentObj, json, response);
				}else{
					func = func || {};

					if (typeof func.before == 'function') {
						var res = func.before(response);

						if (res === false) {
							return false;
						}
					}

					if (func.render !== false) {
						var reg = /^(\.|#)[a-zA-Z0-9_-]*$/;

						$.each(dataTag, function(index, currentTag) {
							if (!json[index] && (typeof currentTag == 'object' && !currentTag.html)) {
								return true;
							}

							var tagObj = parentObj.find((typeof currentTag == 'object' ? currentTag.expr : currentTag));
							tagObj.addClass('updating');

							if (typeof currentTag == 'object') {
								(currentTag.data && json[index]) && tagObj.data(currentTag.data, json[index]);

								if (currentTag.html) {
									if (reg.test(currentTag.html)) {
										var htmlObj = $(currentTag.html);
										if (htmlObj[0].tagName.toLowerCase() == 'select'){
											json[index] && tagObj.html(htmlObj.find('option[value="' + json[index] + '"]').html());
										}else{
											tagObj.html(htmlObj.html());
										}
									}else if (typeof currentTag.html == 'function') {
										tagObj.html(currentTag.html(tagObj, json[index], response.data));
									}else{
										tagObj.html(currentTag.html);
									}
								}
							}else{
								json[index] && tagObj.html(json[index]);
							}
						});

						setTimeout(function(){
							parentObj.find('.updating').removeClass('updating');
						}, 2000);
					}

					if (typeof func.after == 'function') {
						func.after(response);
					}
				}

				xhr.msgLayerIndex && layerClose(xhr.msgLayerIndex);
				xhr.msgLayerIndex = null;
			}

			this.put(url, data, {'success': success});
		},

		/**
		 * 删除后追加内容，主要用于列表页删除某一行数据后，服务端根据当前分页数，获取最后一条数据并返回生成，追加在当前父元素尾部，起到数据补全的作用。
		 * 需要注意的是，在请求服务端时未必需要携带分页数，可以根据业务需要与服务端配合处理，相互约定即可。
		 *
		 * 目前仅使用常规（追加内容）方法进行处理，如有特殊情况传入func参数即可。
		 *
		 * 主要用于后台，或带有列表页的内容。
		 *
		 * @param {String}           url       请求地址
		 * @param {String}           data      请求数据
		 * @param {String or Object} parentObj 父元素的ID或者父元素的对象
		 * @param {String}           tplID     模板ID
		 * @param {Function}         [func]    数据自定义处理方法
		 */
		deleteCommon: function(url, data, parentObj, deleteObj, tplID, func){
			xhr.msgLayerIndex || this.loadPrompt();

			if (typeof parentObj == 'string') {
				parentObj = $(parentObj);
			}

			if (typeof deleteObj == 'string') {
				deleteObj = $(deleteObj);
			}

			var success = function(response){
				this.msgLayerIndex && layerClose(this.msgLayerIndex);
				this.msgLayerIndex = null;

				xhr.layerIndex && layerClose(xhr.layerIndex);
				xhr.layerIndex = null;

				layer.msg(response.msg || '删除成功！', {icon: 6});

				if (typeof func == 'function') {
					func(response, parentObj, tplID);
				}else{
					deleteObj.remove();

					if (!$.isEmptyObject(response.data)) {
						laytpl($('#' + tplID).html()).render(response.data, function(html){
							parentObj.append(html);
						});
					}
				}

				xhr.msgLayerIndex && layerClose(xhr.msgLayerIndex);
				xhr.msgLayerIndex = null;
			}

			this.delete(url, data, {'success': success});
		},

		/**
		 * [createCommon description]
		 *
		 * @param  {[type]} url       [description]
		 * @param  {[type]} data      [description]
		 * @param  {[type]} json      [description]
		 * @param  {[type]} parentObj [description]
		 * @param  {[type]} tplID     [description]
		 * @param  {[type]} func      [description]
		 */
		createCommon: function(url, data, json, parentObj, tplID, func){
			xhr.msgLayerIndex || this.loadPrompt();

			if (typeof parentObj == 'string') {
				parentObj = $(parentObj);
			}

			var success = function(response){
				this.msgLayerIndex && layerClose(this.msgLayerIndex);
				this.msgLayerIndex = null;

				layer.msg(response.msg || '创建成功！', {icon: 6});

				if (typeof func == 'function') {
					func(response, parentObj, tplID);
				}else{
					func = func || {};

					if (typeof func.before == 'function') {
						var res = func.before(response);

						if (res === false) {
							return false;
						}
					}

					laytpl($('#' + tplID).html()).render($.extend(json, response.data || {}), function(html){
						if (func.prepend === true){
							parentObj.prepend(html);
						}else{
							parentObj.append(html);
						}
					});

					if (typeof func.after == 'function') {
						func.after(response);
					}
				}

				xhr.msgLayerIndex && layerClose(xhr.msgLayerIndex);
				xhr.msgLayerIndex = null;
			}

			this.post(url, data, {'success': success});
		},

		/**
		 * 快捷方式 messageRedirect
		 */
		messageRedirect: function(url, data, layerIndex, submitType){
			layerIndex && setLayerClose(layerIndex);
			xhr.msgLayerIndex || this.loadPrompt();

			if (!submitType) {
				this.post(url, data, this.doneState.messageRedirect);
			}else if (submitType == 'put') {
				this.put(url, data, this.doneState.messageRedirect);
			}else if (submitType == 'delete') {
				this.delete(url, data, this.doneState.messageRedirect);
			}
		},

		/**
		 * 快捷方式 messageRefresh
		 */
		messageRefresh: function(url, data, layerIndex, submitType){
			layerIndex && setLayerClose(layerIndex);
			xhr.msgLayerIndex || this.loadPrompt();

			if (!submitType) {
				this.post(url, data, this.doneState.messageRefresh);
			}else if (submitType == 'put') {
				this.put(url, data, this.doneState.messageRefresh);
			}else if (submitType == 'delete') {
				this.delete(url, data, this.doneState.messageRefresh);
			}
		},

		/**
		 * 加载提示
		 */
		loadPrompt: function (option){
			var str, param;
			option = option || {};

			if (!option.str) {
				switch (option.type){
					case 'get':
						str = '正在获取数据，请稍等！';
						break;
					case 'getList':
						str = '正在获取第' + (option.page || 1) + '页数据，请稍等！';
						break;
					default:
						str = '提交中，请稍等！';
				}

				str = '<i class="fa fa-spinner fa-pulse fa-2x fa-fw" style="vertical-align: middle;"></i><span style="font-size:17px; vertical-align:middle; padding-left: 5px;">' + str + '</span>';
			}else{
				if (option.strStyle != false) {
					str = '<i class="fa fa-spinner fa-pulse fa-2x fa-fw" style="vertical-align: middle;"></i><span style="font-size:17px; vertical-align:middle; padding-left: 5px;">' + option.str + '</span>';
				}else{
					str = option.str;
				}
			}

			param = $.extend({time: 0, shade: 0.2, fix: false}, option.param || {});

			xhr.msgLayerIndex = layer.msg(str, param);

			return xhr.msgLayerIndex;
		}
	};

	/**
	 * 执行成功回调（请求成功）
	 *
	 * @param  {Mixed}  done 回调函数或函数数组或字符串
	 * @param  {Object} data 执行结果
	 */
	function executeDone(done, data, sign, xhrObj){
		done = done || {};

		//先对服务端返回的状态码进行判断
		if (data.code == 1) {
			//如果使用者自定义了处理方案，则直接使用。
			if (typeof done.success == 'function') {
				done.success(data);
				return true;
			}else{
				var msg = (typeof done.success == 'string' && done.success) || data.msg || '服务器返回正确';
				var icon = 6;
			}
		}else {
			//如果使用者自定义了处理方案，则直接使用。
			if (typeof done.fail == 'function') {
				done.fail(data);
				return false;
			}else{
				var msg = ((typeof done.fail == 'string' && done.fail) || data.msg || '服务器返回错误') + (eh.debug == 1 ? '（' + data.code + '）' : '');
				var icon = 5;
				xhr.msgLayerIndex && layerClose(xhr.msgLayerIndex);
				xhr.msgLayerIndex = null;
				layer.msg(msg, {icon: icon});
				return false;
			}
		}

		xhr.layerIndex && layerClose(xhr.layerIndex);
		xhr.layerIndex = null;

		//根据用户指定的处理方式，对结果进行处理，一般使用在有数据返回的结果处理，get很少用到。
		if ((typeof done == 'object' && $.isEmptyObject(done)) || done === xhr.doneState.message) {
			layer.msg(msg, {icon: icon});
		}else if (done === xhr.doneState.messageRedirect){
			if (data.data.redirect_url) {
				var wait = data.data.redirect_wait || 3;
				layer.msg(msg + '，' + wait + '秒后自动跳转<a id="eh-xhr-redirect-url" href="' + data.data.redirect_url + '">立即跳转</a>', {time: wait * 1000, icon: icon}, function(){
					location.href = data.data.redirect_url;
				});
			}else{
				layer.msg(msg, {icon: icon});
			}
		}else if (done === xhr.doneState.messageRefresh) {
			var wait = data.data.redirect_wait || 3;
			layer.msg(msg + '，' + wait + '秒后刷新<a id="eh-xhr-redirect-url" href="javascript:void(0);" onclick="location.reload();">立即刷新</a>', {time: wait * 1000, icon: icon}, function(){
				location.reload();
			});
		}

		xhr.msgLayerIndex && layerClose(xhr.msgLayerIndex);
		xhr.msgLayerIndex = null;
	}

	/**
	 * 执行失败回调（请求失败，指无法与服务端进行通讯或服务端返回数据异常，并不代表数据处理的失败。）
	 *
	 * @param  {Mixed}  fail 回调函数或函数数组
	 * @param  {Object} data 执行结果
	 */
	function executeFail(fail, xhrObj, sign){
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
					statusText = '服务端未知错误，请重试！'
			}
		}

		if (!fail) {
			layer.msg(statusText || '请求失败', {icon: 5});
		}else{
			typeof fail == 'function' ? fail(data) : layer.msg(fail, {icon: 5});
		}

		xhr.msgLayerIndex && layerClose(xhr.msgLayerIndex);
		xhr.msgLayerIndex = null;
		return false;
	}

	/**
	 * 设置是否需要关键弹窗
	 *
	 * @param {Number} index layer返回的编号
	 */
	function setLayerClose(index){
		if ($.isNumeric(parseInt(index)) || index == 'all') {
			xhr.layerIndex = index;
		}
	}

	/**
	 * 关闭指定或者全部layer弹窗，并清除保存的编号。
	 *
	 * @param {Number} index layer返回的编号
	 */
	function layerClose(index){
		if (!index) {
			return false;
		}

		if (index == 'all') {
			layer.closeAll();
		}else{
			layer.close(index);
		}
	}

	eh.xhr = xhr;
});