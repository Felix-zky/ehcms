define(['layer', 'jquery', 'dotdotdot'], function(diolog){
	var trigger_sign = 0;

	$(function(){

		$('.description').dotdotdot();

		//当页面发生改变时，截取重新计算，以达到理想显示效果。加100毫秒延迟，可以解决多次触发的情况。
		$(window).resize(function() {
			if (trigger_sign == 0){
				trigger_sign = 1;
				setTimeout(intercept, 100);
			}else{
				return false;
			}
		});
	});

	//执行内容恢复并重新截取
	function intercept(){
		$('.description').html($('.description').data('description'));
		$('.description').dotdotdot();
		trigger_sign = 0;
	}
});