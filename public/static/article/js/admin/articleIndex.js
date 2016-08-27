define(['layer', 'laypage', 'jquery', 'dotdotdot'], function(diolog, laypage){
	var trigger_sign = 0;

	$(function(){
		setListHeight();

		$('.description').dotdotdot();

		//当页面发生改变时，截取重新计算，以达到理想显示效果。加100毫秒延迟，可以解决多次触发的情况。
		$(window).resize(function() {
			if (trigger_sign == 0){
				trigger_sign = 1;
				setTimeout(intercept, 100);
				setTimeout(setListHeight, 100);
			}else{
				return false;
			}
		});

		laypage({
			cont: 'list-pages', //容器。值支持id名、原生dom对象，jquery对象,
			pages: 68, //总页数
			curr: location.hash.replace('#!page=', ''), //获取hash值为fenye的当前页
			hash: 'page', //自定义hash值
			skin: '#AF0000',
			jump: function(obj){
				console.log(obj.curr);
			}
		});
	});

	//执行内容恢复并重新截取
	function intercept(){
		$('.description').html($('.description').data('description'));
		$('.description').dotdotdot();
		trigger_sign = 0;
	}

	//计算除去头部和分页后的内容高度
	function setListHeight(){
		var listHeight = $(window).outerHeight() - $('header').outerHeight() - 10 - 80;
		$('.list-main ul').height(listHeight);
	}
});