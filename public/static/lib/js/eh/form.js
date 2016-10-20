define(['jquery', 'validate.zh', 'eh' , 'layer'], function(){

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

			return $.param(data);
		},

		/**
		 * 清除指定或全部表单数据
		 *
		 * @param  {String} element form元素的id(#xxx)或class(.xxx)
		 */
		emptyFormData: function(element){
			var obj = (element && $(element).length > 0) ? $(element) : $('form');

			obj.find('input[type="text"], textarea').val('');
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

			layer.alert(error, {title: '存在以下错误，请修正后再次提交！'});
			return false;
		},

		/**
		 * 清除设置的validate高亮样式
		 *
		 * @param  {String} sign    参数标示，默认值是bootstrap。
		 * @param  {String} element form元素的id(#xxx)或class(.xxx)
		 */
		validateHighlightRemove: function(sign, element){
			sign = sign || 'bootstrap';
			var obj = (element && $(element).length > 0) ? $(element) : $('form');

			switch(sign){
				//目前bootstrap删除的是在unhighlight中定义的内容，如果删除unhighlight或者更改它这里也要对应修改。
				case 'bootstrap':
					obj.find(".has-success").removeClass('has-success').find('span.form-control-feedback, em.error').remove();
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
						element.parents( ".form-group" ).addClass( "has-feedback" );

						if ( element.prop( "type" ) === "checkbox" ) {
							error.insertAfter( element.parent( "label" ) );
						} else {
							error.insertAfter( element );
						}

						//判断当前错误元素紧邻元素span是否存在，如果不存在，就添加一个新的，用于显示错误的信息！
						if ( !$( element ).next( "span" )[0] ) {
							$( "<span class='fa fa-close form-control-feedback'></span>" ).insertAfter( element );
						}
					},
					success: function ( label, element ) {
						//判断当前元素紧邻元素span是否存在，如果不存在，就添加一个新的，用于显示正确的信息！
						if ( !$( element ).next( "span" )[0] ) {
							$( "<span class='fa fa-check form-control-feedback'></span>" ).insertAfter( element );
						}
						label.removeClass('help-block');
					},
					highlight: function ( element ) {
						$( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
						$( element ).next( "span" ).addClass( "fa-close" ).removeClass( "fa-check" );
						$( element ).siblings('em').addClass('help-block');
					},
					unhighlight: function ( element ) {
						$( element ).parents( ".form-group" ).addClass( "has-success" ).removeClass( "has-error" );
						$( element ).next( "span" ).addClass( "fa-check" ).removeClass( "fa-close" );
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

	eh.form = form;

});