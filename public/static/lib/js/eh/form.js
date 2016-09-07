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
		 * @return {Boolean}        检查是否通过
		 */
		checkFormData: function(element, option){
			if (element && $(element).length > 0) {
				if (option.rules && typeof option.rules == 'object') {
					var validateObj = $(element).validate({
						rules: option.rules,
						errorElement: "em",
						errorPlacement: function ( error, element ) {
							// Add the `help-block` class to the error element
							error.addClass( "help-block" );

							// Add `has-feedback` class to the parent div.form-group
							// in order to add icons to inputs
							element.parents( ".col-sm-5" ).addClass( "has-feedback" );

							if ( element.prop( "type" ) === "checkbox" ) {
								error.insertAfter( element.parent( "label" ) );
							} else {
								error.insertAfter( element );
							}

							// Add the span element, if doesn't exists, and apply the icon classes to it.
							if ( !element.next( "span" )[ 0 ] ) {
								$( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
							}
						},
						success: function ( label, element ) {
							// Add the span element, if doesn't exists, and apply the icon classes to it.
							if ( !$( element ).next( "span" )[ 0 ] ) {
								$( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
							}
						},
						highlight: function ( element, errorClass, validClass ) {
							$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
							$( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
						},
						unhighlight: function ( element, errorClass, validClass ) {
							$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
							$( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
						}
					});
					validateObj.settings.messages = (option.messages && typeof option.messages == 'object') ? option.messages : {};
				}else{
					eh.debugPrint('验证规则必须设置！');
				}
			}else {
				eh.debugPrint('表单元素不存在！');
			}
		}
	}

	eh.form = form;

});