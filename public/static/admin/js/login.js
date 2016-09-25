define(['jquery', 'eh.xhr', 'eh.form', 'validate.zh', 'layer'], function(){

	$(function(){
		//多动画随机展现登录框
		var animate = [
			'fadeIn',
			'zoomIn',
			'zoomInLeft',
			'zoomInRight',
			'zoomInDown',
			'zoomInUp'
		];

		$('section.login').css({
			'animation-name': animate[Math.floor(Math.random()*6)],
		});

		
		$('button').click(function(){
			var validate = $("form").validate({
				rules:{
					'username': 'required',
					'password': 'required'
				},
				messages:{
					'username': '用户名不能为空',
					'password': '密码不能为空'
				}
			});

			if (validate.checkForm() == false){
				layer.alert(eh.form.validateError(validate.errorMap));
			}else{
				eh.xhr.post();
			}
			return false;
		});


	});
});