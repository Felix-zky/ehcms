define(['jquery', 'layer'], function(){
	$(document).ready(function(){
		contentWidthCompute();

		$(window).resize(function() {
			contentWidthCompute();
		});

		$('.menu-tree li').on('transitionend', function(){
			$(this).removeClass('wait');
		});

		/** 菜单树li元素点击触发（无限级） */
		$('.menu-tree li').click(function(e){
			if (!$('.menu-tree').is('.open')){
				return false;
			}

			e.stopPropagation();

			var childrenUI = $(this).children('ul');

			if (childrenUI.length > 0){
				if ($('.menu-tree .wait').length > 0){
					return false;
				}

				$(this).addClass('wait');

				//当前如果是打开状态被点击就隐藏掉
				if ($(this).is('.selected')) {
					$(this).css({
						height: ($(this).outerHeight() - childrenUI.outerHeight()) + 'px'
					});
					$(this).children('.row').children('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
					$(this).removeClass('selected');
					$(this).find('.selected').removeClass('selected');
				}else{
					//如果当前是关闭状态被点击首先判断是否存在已经打开的菜单列表，如果存在将其隐藏掉。
					var selected = $(this).siblings('li.selected');

					selected.css({
						height: (selected.outerHeight() - selected.children('ul').outerHeight()) + 'px'
					});

					selected.children('.row').children('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');

					selected.find('.selected').removeClass('selected');

					//当前触发对象获取被被选中状态
					$(this).css({
						height: ($(this).outerHeight() + childrenUI.outerHeight()) + 'px'
					});
					$(this).addClass('selected').siblings('li').removeClass('selected');
					$(this).children('.row').children('i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
				}
			}else{
				var url = $(this).data('url');

				$(this).addClass('selected').siblings('li').removeClass('selected');
					
				if (url) {
					if ($('iframe').attr('src') != url) {
						iframe(url);
					}else{
						layer.confirm('您确定要重新打开本页面吗？', {btn: ['确定', '取消']}, 
							function(index){
								iframe(url);
								layer.close(index);
							}
						);
					}
				}
			}
		});
	});
	
	/**
	 * 计算右侧内容区宽度
	 */
	function contentWidthCompute(){
		var windowWidth = $(window).outerWidth();
		$('.content').css({
			width: (windowWidth - 218) + 'px'
		});
	}

	/**
	 * 隐藏iframe框内容并显示加载提醒，加载完成后关闭提醒并显示iframe框新内容
	 */
	function iframe(url){
		$('iframe').css({
			opacity: 0
		});

		$('iframe').attr('src', url);

		$('.loading').show();

		$('iframe').load(function(){
			$('iframe').css({
				opacity: 1
			});
			$('.loading').hide();
		});
	}
});