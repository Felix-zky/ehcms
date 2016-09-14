define(['layer', 'jquery', 'dotdotdot', 'eh.xhr', 'laytpl'], function(diolog, laypage){
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

	function getArticleList(){
		var done = {
			success: function(){
				listRender(data);
			}
		}
		eh.xhr.get('article', {pages: 1}, '', done);
	}

	function listRender(data){
		laytpl($('#tpl-article-list')).render(data, function(render){
			$('section.list-main').html(render);
		});
	}
});