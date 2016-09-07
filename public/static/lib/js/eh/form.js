define(['jquery', 'validate.zh', 'eh'], function(){

	var form = {
		/**
		 * 清除指定或全部表单数据
		 *
		 * @param  {String} element 元素的id(#xxx)或class(.xxx)
		 */
		emptyForm: function(element){
			var obj = (element && $(element).length > 0) ? $(element) : $('form');

			obj.find('input[type="text"], textarea').val('');
		},

		/**
		 * 检查表单数据
		 * 该函数主要设置通用内容，比如错误提醒方式、触发方式等。
		 * 检验的内容及检验方式由调用者提供
		 *
		 * @param  {String} element 表单的元素
		 * @param  {Object} option  配置参数
		 * @param  {String} sign    参数集标示
		 */
		checkFormData: function(element, option, sign){
			if (element && $(element).length > 0) {
				if (option.rules && typeof option.rules == 'object') {
					$(element).validate($.extend(validateParam(sign || 'bootstrap'), option));
				}else{
					eh.debugPrint('验证规则必须设置！');
				}
			}else {
				eh.debugPrint('表单元素不存在！');
			}
		}
	}

	/**
	 * 表单检查个性化参数调用
	 *
	 * @param  {String} sign 参数的标示
	 * @return {Object}      返回执行标示代表的参数
	 */
	function validateParam(sign){
		switch(sign){
			case 'bootstrap':
				return {
					errorElement: "em",
					errorPlacement: function ( error, element ) {
						// Add the `help-block` class to the error element
						error.addClass( "help-block" );

						// Add `has-feedback` class to the parent div.form-group
						// in order to add icons to inputs
						element.parents( ".col-sm-11" ).addClass( "has-feedback" );

						if ( element.prop( "type" ) === "checkbox" ) {
							error.insertAfter( element.parent( "label" ) );
						} else {
							error.insertAfter( element );
						}

						// Add the span element, if doesn't exists, and apply the icon classes to it.
						if ( !element.next( "span" )[ 0 ] ) {
							$( "<span class='fa fa-close form-control-feedback'></span>" ).insertAfter( element );
						}
					},
					success: function ( label, element ) {
						// Add the span element, if doesn't exists, and apply the icon classes to it.
						if ( !$( element ).next( "span" )[ 0 ] ) {
							$( "<span class='fa fa-check form-control-feedback'></span>" ).insertAfter( $( element ) );
						}
					},
					highlight: function ( element, errorClass, validClass ) {
						$( element ).parents( ".col-sm-11" ).addClass( "has-error" ).removeClass( "has-success" );
						$( element ).next( "span" ).addClass( "fa-close" ).removeClass( "fa-check" );
					},
					unhighlight: function ( element, errorClass, validClass ) {
						$( element ).parents( ".col-sm-11" ).addClass( "has-success" ).removeClass( "has-error" );
						$( element ).next( "span" ).addClass( "fa-check" ).removeClass( "fa-close" );
					}
				};
				break;
			default:
				return {};
		}
	}

	eh.form = form;

});