define(['jquery', 'validate.zh', 'eh', 'layer'], function(){

	validateInit();

	var form = {
		/**
		 * 提取表单数据
		 *
		 * @param {Object} option  设置
		 * @param {String} element 表单元素，默认为form
		 */
		extractData: function(option, element){
			var obj = (element && $(element).length > 0) ? $(element) : $('form'),
				data = $(obj).serializeArray(),
				onlyData = [], exceptData = [] ,extendData = [], extendFromElement = [];
			
			//为option赋初始值，未定义元素直接访问其属性会报错。
			option = option || {};

			//只允许提取某些数据
			if ($.isArray(option.only)) {
				$.each(data, function() {
					if ($.inArray(this.name, option.only) >= 0) {
						onlyData.push(this);
					}
				});
				data = onlyData;
			}

			//排除某些数据
			if ($.isArray(option.except)) {
				$.each(data, function() {
					if ($.inArray(this.name, option.except) == -1) {
						exceptData.push(this);
					}
				});
				data = exceptData;
			}

			//扩展数据，将一些非表单内的页面数据组合进去
			//扩展存在问题，使用到的时候再处理
			if ($.isArray(option.extend)) {
				$.each(option.extend, function() {
					if (this.type == 'form') {
						extendFromElement.push(this.element);
					}else{
						extendData.push(
							{
								'name' : this.name,
								'value' : $(this.element).html()
							}
						);
					}
				});
				data = data.concat(extendData);
				if (extendFromElement.length > 0) {
					data = data.concat($(extendFromElement.join(',')).serializeArray());
				}
			}

			data = formatData(data);

			switch(option.returnType){
				case 'all':
					return {
						json: data.json,
						param: data.param
					};
				case 'json':
					return data.json;
				default:
					return data.param;
			}
		},

		/**
		 * 清除指定或全部表单数据
		 *
		 * @param  {String} element form元素的id(#xxx)或class(.xxx)
		 */
		emptyFormData: function(element){
			var obj = (element && $(element).length > 0) ? $(element) : $('form');

			obj.find('input[type="text"], input[type="password"], textarea').val('');
			obj.find('select').val(0);
			obj.find('input:checked').prop('checked', false);
		},

		/**
		 * 获取验证错误并生成列表
		 *
		 * @param  {Object} errorMap validate
		 * @param  {String} [type]   生成错误的方式
		 * @return {String}          生成后的错误列表
		 */
		validateError: function(errorMap, type){
			var i = 1, str = [], error;

			$.each(errorMap, function(index, val){
				str.push(i + '、' + val);
				i++;
			});

			error = str.join('<br />');
			
			if (type) {
				switch(type){
					case 'return':
						return error;
						break;
				}
				return false;
			}
			
			layer.alert(error, {title: '发生错误 - 请修正后再次提交'});
			return false;
		},

		/**
		 * 清除设置的validate高亮样式
		 *
		 * @param  {String} element form元素的id(#xxx)或class(.xxx)。
		 * @param  {String} sign    参数标示，默认值是bootstrap。
		 * @param  {String} relation 为parent将向上查找父类的样式，为空或其他都将向下查找。
		 */
		validateHighlightRemove: function(element, sign, relation){
			sign = sign || 'bootstrap';
			var obj = (element && $(element).length > 0) ? $(element) : $('form');

			switch(sign){
				//目前bootstrap删除的是在unhighlight中定义的内容，如果删除unhighlight或者更改它这里也要对应修改。
				case 'bootstrap':
					var element = relation == 'parent' ? obj.parents(".has-success, .has-error") : obj.find(".has-success, .has-error")
					element.removeClass('has-success has-error').find('span.form-control-feedback, em.error').remove();
					break;
			}
		},

		/**
		 * 检查表单数据
		 * 该函数主要设置通用内容，比如错误提醒方式、触发方式等。
		 * 检验的内容及检验方式由调用者提供
		 *
		 * @param  {Object} option  配置参数
		 * @param  {String} sign    参数集标示
		 * @param  {String} element 表单的元素
		 */
		checkFormData: function(option, sign, element){
			element = element || 'form';
			if ($(element).length > 0) {
				if (option.rules && typeof option.rules == 'object') {
					return $(element).validate($.extend(validateParam(sign || 'bootstrap'), option));
				}else{
					eh.debugPrint('验证规则必须设置！');
				}
			}else {
				eh.debugPrint('表单元素不存在！');
			}
		},

		/**
		 * 快捷清除，同时清除表单以及样式
		 * 默认清除bootstrap验证结果和表单全部内容。暂不考虑其他组合方式，如需自定义，可以分别调用清除表单和清除验证。
		 * 
		 */
		dataAndValidateRemove: function(){
			this.emptyFormData();
			this.validateHighlightRemove();
		}
	}

	/**
	 * 验证插件初始化
	 */
	function validateInit(){
		$.validator.setDefaults({
			'ignore': '.no-validate'
		});
	}

	/**
	 * 表单检查个性化参数调用
	 *
	 * @param  {String} sign 参数的标示
	 * @return {Object}      返回执行标示代表的参数
	 */
	function validateParam(sign){
		switch(sign){
			/** bootstrap框架专用，用于常用表单。 */
			case 'bootstrap':
				return {
					errorElement: "em",
					errorPlacement: function ( error, element ) {
						// 添加`help-block`类到错误的元素上
						error.addClass( "help-block" );

						// 添加`has-feedback`类到父元素上。
						var styleElement = element.parents('.validate-style')[0] ? element.parents('.validate-style') : element.parents( ".form-group" )
						styleElement.addClass( "has-feedback" );

						if (element.prop( "type" ) === "checkbox" || element.prop( "type" ) === "radio") {
							if ($(element).parents('.validate-box')[0]) {
								if (!$(element).parents('.validate-box').children("span")[0]) {
									$(element).parents('.validate-box').append("<span class='fa fa-close form-control-feedback'></span>");
								}
								$(element).parents('.validate-box').append(error);
							}else{
								if (!$( element ).parent().children("span")[0]) {
									$( element ).parent().append("<span class='fa fa-close form-control-feedback'></span>");
								}
								$( element ).parent().append(error);
							}
						}else{
							error.insertAfter( element );
							if ( !$( element ).next( "span" )[0] ) {
								$( "<span class='fa fa-close form-control-feedback'></span>" ).insertAfter( element );
							}
						}
					},
					success: function ( label, element ) {
						if ($( element ).is(':radio') || $( element ).is(':checkbox')) {
							if ($(element).parents('.validate-box')[0]) {
								if (!$(element).parents('.validate-box').children("span")[0]) {
									$(element).parents('.validate-box').append("<span class='fa fa-check form-control-feedback'></span>");
								}
							}else{
								if (!$( element ).parent().children("span")[0]) {
									$( element ).parent().append("<span class='fa fa-check form-control-feedback'></span>");
								}
							}
						}else{
							//判断当前元素紧邻元素span是否存在，如果不存在，就添加一个新的，用于显示正确的信息！
							if ( !$( element ).next( "span" )[0] ) {
								$( "<span class='fa fa-check form-control-feedback'></span>" ).insertAfter( element );
							}
						}
						label.removeClass('help-block');
					},
					highlight: function ( element ) {
						if ($(element).parents('.validate-style')[0]) {
							$(element).parents('.validate-style').addClass( "has-error" ).removeClass( "has-success" );
							var parents = $(element).parents('.form-group');
							if (parents.find('.validate-style').length != parents.find('.has-error').length) {
								parents.find('label').addClass('error').removeClass('success');
							}
						}else{
							$(element).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
						}

						if ($( element ).is(':radio') || $( element ).is(':checkbox')) {
							if ($(element).parents('.validate-box')[0]) {
								$(element).parents('.validate-box').children('span').addClass( "fa-close" ).removeClass( "fa-check" );
								$(element).parents('.validate-box').children('em').addClass('help-block');
							}else{
								$(element).parent().children('span').addClass( "fa-close" ).removeClass( "fa-check" );
								$(element).parent().children('em').addClass('help-block');
							}
						}else{
							$( element ).next( "span" ).addClass( "fa-close" ).removeClass( "fa-check" );
							$( element ).siblings('em').addClass('help-block');
						}
					},
					unhighlight: function ( element ) {
						if ($(element).parents('.validate-style')[0]) {
							$(element).parents('.validate-style').addClass( "has-success" ).removeClass( "has-error" );
							var parents = $(element).parents('.form-group');
							if (parents.find('.validate-style').length == parents.find('.has-success').length) {
								parents.find('label').addClass("success").removeClass('error');
							}
						}else{
							$(element).parents( ".form-group" ).addClass( "has-success" ).removeClass( "has-error" );
						}

						if ($( element ).is(':radio') || $( element ).is(':checkbox')) {
							if ($(element).parents('.validate-box')[0]) {
								$(element).parents('.validate-box').children('span').addClass( "fa-check" ).removeClass( "fa-close" );
							}else{
								$(element).parent().children('span').addClass( "fa-check" ).removeClass( "fa-close" );
							}
						}else{
							$( element ).next( "span" ).addClass( "fa-check" ).removeClass( "fa-close" );
						}
					},
					onfocusout: function( element ) {
						if ($.isEmptyObject($(element).rules())) {
							return false;
						}
						if ( !this.checkable( element ) && ( element.name in this.submitted || !this.optional( element ) ) ) {
							this.element( element );
						}
					}
				};
				break;
			/** 仅提交触发验证，一般此方法配合validate.checkForm使用。 */
			case 'submit':
				return {
					onkeyup: false,
					onfocusout: false,
					onclick: false
				};
				break;
			default:
				return {};
		}
	}

	/**
	 * 处理extractData函数使用serializeArray函数获取的json对象
	 * json对象的name为键名
	 * 
	 * @param  {Array}  json serializeArray获取的表单json对象
	 * @return {Object}      处理后的对象
	 */
	function formatData(data){
		var json = {}, param = [], reg = /\[\]$/;

		if (typeof data == 'object') {
			$.each(data, function(index, val) {
				if (val.value !== '') {
					param.push(val);

					if (reg.test(val.name)) {
						var name = val.name.slice(0, -2);
						if (name) {
							if (!$.isArray(json[name])) {
								json[name] = [];
							}
							json[name].push(val.value);
						}
					}else{
						json[val.name] = val.value;
					}
				}
			});

			data = {
				json: json,
				param: $.param(param)
			}
			return data;
		}
	}

	eh.form = form;
});