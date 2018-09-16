define(['jquery', 'eh.xhr', 'eh.form', 'validate.zh', 'gt'], function(){

	var geetObj = {};
	var submitType;
	var time = 60;
	var countDown;

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

	$('#code').keydown(function(e) {
		if ($(this).val().length >= 6 && e.keyCode != 8 && e.keyCode != 9 && e.keyCode != 46){
			return false;
		}
	});

	$('#phone').keydown(function(e) {
		if ($(this).val().length >= 11 && e.keyCode != 8 && e.keyCode != 9 && e.keyCode != 46){
			return false;
		}
	});

	$('#code').keyup(function() {
		var v = $(this).val(),
			phone = $('.register #phone').val();

		if (phone.length == 11 && v.length == 6) {
			clearInterval(countDown);
			$('.send-code').attr({disabled: false}).hide();
			$('.register .submit').show();
		}else{
			clearInterval(countDown);
			returnSendCode();
		}
	});

	$('#phone').keyup(function() {
		if ($(this).val().length < 11) {
			clearInterval(countDown);
			returnSendCode();
		}else if ($(this).val().length == 11 && $('.register #code').val().length == 6) {
			if ($('.register button').hasClass('send-code')){
				clearInterval(countDown);
				$('.send-code').attr({disabled: false}).hide();
				$('.register .submit').show();
			}
		}
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
						if (submitType == 'code') {
							sendCode();
						}else if (submitType == 'register'){
							eh.xhr.post($('.register form').attr('action'), eh.form.extractData({}, '.register form'), eh.xhr.doneState.messageRedirect);
						}else{
							eh.xhr.post($(form).attr('action'), eh.form.extractData(), eh.xhr.doneState.messageRedirect);
						}
					});
				});
			},
			fail: function(response){
				layer.alert(response.msg, {icon: 0});
			}
		});
	}

	function sendCode(){
		eh.xhr.post('/member/sendcode.html', eh.form.extractData({extend: {name: 'phone', value: $('.register #phone').val()}}), {
			success: function(response){
				submitType = '';
				if (response.code == 1){
					$('.send-code').attr({disabled: true});
					countDown = setInterval(function(){
						if (time == -1) {
							$('.send-code').attr({disabled: false}).html('重发验证码');
							clearInterval(countDown);
							time = 60;
						}else{
							$('.send-code').html('（' + time + '）秒后可重发');
							--time;
						}
					}, 1000);
					layer.alert('短信发送成功');
				}else{
					layer.alert('短信发送失败');
				}
			}
		});
	}

	function returnSendCode(){
		if ($('.register button').hasClass('submit')){
			if (time == 60) {
				$('.register .submit').hide();
				$('.register .send-code').show();
			}else{
				$('.register .submit').hide();
				$('.register .send-code').attr({disabled: true}).show();
				countDown = setInterval(function(){
					if (time == -1) {
						$('.send-code').attr({disabled: false}).html('重发验证码');
						clearInterval(countDown);
						time = 60;
					}else{
						$('.send-code').html('（' + time + '）秒后可重发');
						--time;
					}
				}, 1000);
			}
		}
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
			var validate = $(".login form").validate({
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
				eh.form.validateError(validate.errorMap);
			}else{
				if (!$.isEmptyObject(geetObj)){
					geetObj.verify();
				}else{
					eh.xhr.post($('.login form').attr('action'), eh.form.extractData({}, '.login form'), eh.xhr.doneState.messageRedirect);
				}
			}
			return false;
		});

		$('.register .submit').click(function(){
			var validate = $(".register form").validate({
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
				eh.form.validateError(validate.errorMap);
			}else{
				if (!$.isEmptyObject(geetObj)){
					submitType = 'register';
					geetObj.verify();
				}else{
					eh.xhr.post($('.register form').attr('action'), eh.form.extractData({}, '.register form'), eh.xhr.doneState.messageRedirect);
				}
			}
			validate.destroy();
			return false;
		});

		$('.register .send-code').click(function() {
			var validate = $(".register form").validate({
				rules:{
					phone: {
						required: true,
						rangelength: [11, 11]
					},
				},
				messages:{
					phone: {
						required: '手机号必须填写',
						rangelength: '手机号长度只能为11位'
					},
				}
			});

			if (validate.checkForm() == false){
				eh.form.validateError(validate.errorMap);
			}else{
				if (!$.isEmptyObject(geetObj)){
					submitType = 'code';
					geetObj.verify();
				}else{
					sendCode();
				}
			}
			validate.destroy();
		});
	});
});