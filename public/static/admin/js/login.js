define(['jquery', 'eh.xhr', 'eh.form', 'validate.zh', 'gt'], function(){

	var geetObj = {};

	$('#register').click(function(){
		$('.login').animateCss('flipOutY', function(){
			$('.login').hide();
			if ($('.login .geetest_form').length == 1) {
				$('.register form').append($('.login .geetest_form'));
			}
			$('.register').animateCss('flipInY').show();
		});
	});

	$('#login').click(function(){
		$('.register').animateCss('flipOutY', function(){
			$('.register').hide();
			if ($('.register .geetest_form').length == 1) {
				$('.login form').append($('.register .geetest_form'));
			}
			$('.login').animateCss('flipInY').show();
		});
	});

	function geetest(form){
		eh.xhr.get('/login/geetest.html', {}, {
			success: function(response){
				var data = response.data;

				initGeetest({
					gt: data.gt,
					challenge: data.challenge,
					offline: !data.success,
					new_captcha: true,
					product: 'bind'
				}, function (captchaObj) {
					geetObj = captchaObj;
					captchaObj.bindForm(form);
					captchaObj.onSuccess(function(){
						eh.xhr.post($(form).attr('action'), eh.form.extractData(), eh.xhr.doneState.messageRedirect);
					});
				});
			},
			fail: function(response){
				layer.alert(response.msg, {icon: 0});
			}
		});
	}

	$(function(){
		geetest('.login form');

		//多动画随机展现登录框
		var animate = [
			'fadeIn',
			'zoomIn',
			'zoomInLeft',
			'zoomInRight',
			'zoomInDown',
			'zoomInUp'
		];

		$('.login')
		.css({
			'margin-top': ($(window).height() - $('.login').height()) / 2 + 'px'
		})
		.show()
		.animateCss(animate[Math.floor(Math.random()*6)]);

		$('.register')
		.css({
			'margin-top': ($(window).height() - $('.register').height()) / 2 + 'px'
		})

		$('.login button').click(function(){
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
				if (!$.isEmptyObject(geetObj)){
					geetObj.verify();
				}else{
					eh.xhr.post($('form').attr('action'), eh.form.extractData(), eh.xhr.doneState.messageRedirect);
				}
			}
			return false;
		});
	});
});