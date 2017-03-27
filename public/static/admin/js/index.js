define(['laytpl', 'jquery', 'layer', 'jquery.contextMenu', 'lodash', 'eh'],function(laytpl){
		var	screenHeight = window.screen.height,
		contentMenuItemsConfigure = {
			'open': {
				icon: 'folder-open-o',
				keyboard: 'Enter'
			},
			'trash': {
				icon: 'trash',
				keyboard: 'Delete'
			},
			'refresh': {
				icon: 'refresh',
				keyboard: 'F5'
			},
			'sort': {
				icon: 'sort'
			},
			'wallpaper': {
				icon: 'gears'
			},
			'close': {
				icon: 'close'
			},
			'reset': {
				icon: 'refresh'
			},
			'min': {
				icon: 'compress'
			},
			'max': {
				icon: 'arrows-alt'
			},
			'restore': {
				icon: 'recycle'
			}
		},
		contentMenuSign = {},
		taskbarTime = $('#taskbar .taskbar-right .time'),
		taskbarTimeIndex,
		iframeActiveList = [];

		eh.noRightKey();
	$(document).ready(function(){
		$('body').on('mousedown', '.layui-layer-page', function(){
			if ($(this).css('z-index') < layer.zIndex && $(this).attr('times') != iframeActiveList[0]){
				var index = $(this).attr('times');
				$('#taskbar li[data-index="' + index + '"]').click();
			}
		});

		layer.min = function(index, options){
			var layerDom = $('#layui-layer' + index);
			layerDom.hide();
			iframeActiveList = _.difference(iframeActiveList, [index]);
			if (iframeActiveList[0] > 0) {
				$('#taskbar li[data-index="' + iframeActiveList[0] + '"]').addClass('active');
			}
			$('#taskbar .list li[data-index = '+index+']').removeClass('active');
		}

		sortDesktopIcon();
		checkFullScreen();

		$(window).resize(function() {
			sortDesktopIcon();
			checkFullScreen();
		});

		/** 点击屏幕空白区域，将当前选中的图标状态去除 */
		$('#desktop-icon').click(function(e){
			e.target.id == 'desktop-icon' && $(this).find('li.selected').removeClass('selected');
		})

		/** 图标状态选中及去除同辈元素已选中的 */
		$('#desktop-icon').on('click', 'li', function() {
			$(this).addClass('selected').siblings().removeClass('selected');
		});

		/** 双击图标触发 */
		$('#desktop-icon').on('dblclick', 'li', function() {
			var icon = $(this).find('img').attr('src'),
				name = $(this).find('span').html();
				
			var xhr = $.post('/admin/Index/getMenu', {moduleID: $(this).data('module-id')}, '', "json");

			xhr.done(function(response){
				if (response.code == 1){
					currentIframe = openiframe(response.data);
					taskbarHtml = '<li data-index=' + currentIframe + ' class="active"><img src="' + icon + '" /><span>' + name + '</span></li>';

					iframeActiveList.unshift(currentIframe);
			
					$('#taskbar .list ul li.active').removeClass('active');
					$('#taskbar .list ul').append(taskbarHtml);
				}
			});
		});

		/** 图标右键 */
		$.contextMenu({
			selector: '#desktop-icon li',
			items: {
				open: {
					name: '打开'
				},
				sep1: "---------",
				trash: {
					name: '卸载'
				}
			},
			zIndex: 100000000, //确保遮罩层和弹出菜单在layer弹窗之上。
			callback: function(itemKey, opt){
				switch (itemKey){
					case 'open':
						$(this).dblclick();
						break;
				}	
			},
			events: {
				show: function(options){
					var sign = checkContentMenuSign(options.ns);

					$(this).addClass('selected').siblings().removeClass('selected');
					sign === true || createMenuIconAndkeyboard(sign, options.items);
				}
			}
		});

		/** 桌面空白区域右键 */
		$.contextMenu({
			selector: '#desktop-icon',
			items: {
				refresh: {
					name: '刷新'
				},
				sep1: "---------",
				sort: {
					name: '排序方式'
				},
				sep1: "---------",
				wallpaper: {
					name: '桌面壁纸'
				}
			},
			zIndex: 100000000, //确保遮罩层和弹出菜单在layer弹窗之上。
			reposition: false,
			events: {
				show: function(options){
					var sign = checkContentMenuSign(options.ns);

					$(this).find('li.selected').removeClass('selected');
					sign === true || createMenuIconAndkeyboard(sign, options.items);
				}
			}
		});

		/** 任务栏列表右键 */
		$.contextMenu({
			selector: '#taskbar li',
			items: {
				close: {
					name: '关闭',
				},
				sep1: "---------",
				min: {
					name: '最小化',
					className: 'min'
				},
				max: {
					name: '最大化',
					className: 'max'
				},
				restore: {
					name: '还原',
					className: 'restore'
				},
				sep2: "---------",
				reset: {
					name: '重置窗口'
				}
			},
			callback: function(itemKey, opt){
				var index = $(this).data('index');
				switch (itemKey){
					case 'close':
						layer.close(index);
						$('#taskbar li[data-index = ' + index + ']').remove();
						break;
					case 'min':
						$('#layui-layer' + index).is(':visible') && $('#taskbar li[data-index = ' + index + ']').click();
						break;
					case 'max':
						var layero = $('#layui-layer' + index);
						layero.find('.layui-layer-maxmin').length == 0 && layero.find('.layui-layer-max').click();
						break;
					case 'reset':
						layer.style(index, {
							width: Math.ceil($(window).width() * .7) + 'px',
							height: Math.ceil($(window).height() * .8) + 'px',
						});

						layer.style(index, {
							left: Math.ceil($(window).width() - $(window).width() * .7) / 2 + 'px',
							top: Math.ceil($(window).height() - $(window).height() * .8) / 2 + 'px',
						});

						$('#layui-layer' + index).find('.layui-layer-maxmin').removeClass('layui-layer-maxmin');
						break;
				}
			},
			events: {
				show: function(options){
					var sign = checkContentMenuSign(options.ns);
					sign === true || createMenuIconAndkeyboard(sign, options.items);

					var index = $(this).data('index'),
						layero = $('#layui-layer' + index);
					if (!layero.is(':visible')){
						options.$menu.find('.min').addClass('hide');
					}else if(layero.find('.layui-layer-maxmin').length > 0){
						options.$menu.find('.max').addClass('hide');
					}else{
						options.$menu.find('.restore').addClass('hide');
					}
				},
				hide: function(options){
					options.$menu.find('li').removeClass('hide');
				}
			}
		});

		/** 键盘按下事件 */
		$('body').keydown(function(e) {
			//阻止F5刷新页面
			//e.keyCode == 116 && e.preventDefault();getDesktopIcon();
		});

		/** 任务栏切换、当前任务显示与隐藏 */
		$('#taskbar').on('click', 'li', function(){
			var index = $(this).data('index'),
				layero = $('#layui-layer' + index);
			if ($(this).is('.active')) {
				$(this).removeClass('active');
				layero.hide();
				iframeActiveList.shift();
				if (iframeActiveList[0] > 0) {
					$('#taskbar li[data-index="' + iframeActiveList[0] + '"]').addClass('active');
				}
			}else{
				for (var i = 1, length = iframeActiveList.length; i < length; i++) {
					if (iframeActiveList[i] == index) {
						iframeActiveList.splice(i, 1);
						break;
					}
				}
				iframeActiveList.unshift(index);
				$(this).addClass('active').siblings('li.active').removeClass('active');
				!layero.is(':visible') && layero.show();
				if (layero.css('z-index') < layer.zIndex) {
					layer.zIndex++;
					layero.css('z-index', layer.zIndex);
				}
			}
		});

		$('body').on('transitionend', '.menu-tree li', function(){
			$(this).removeClass('wait');
		});

		/** 菜单树li元素点击触发 */
		$('body').on('click', '.menu-tree li', function(e){
			if (!$(this).parents('.menu-tree').is('.open')){
				return false;
			}

			e.stopPropagation();

			var childrenUI = $(this).children('ul');

			if (childrenUI.length > 0){
				if ($(this).parents('.menu-tree').find('.wait').length > 0){
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
				var url = $(this).data('url'), //目标url
					title = $(this).find('span').html(), //目标页面标题
					layerIndex = $(this).parents('.layui-layer-page').attr('times'); //当前的layer弹窗编号

				$(this).addClass('selected').siblings('li').removeClass('selected');
					
				if (url) {
					if ($(this).parents('#iframe-main').find('iframe').attr('src') != url) {
						alterCurrentIframeTitle(layerIndex, title);
						iframe.call($(this).parents('#iframe-main'), url);
					}else{
						var that = $(this);
						layer.confirm('您确定要重新打开本页面吗？', {btn: ['确定', '取消'], zIndex: layer.zIndex}, 
							function(index){
								alterCurrentIframeTitle(layerIndex, title);
								iframe.call(that.parents('#iframe-main'), url);
								layer.close(index);
							}
						);
					}
				}
			}
		});
	});

	/**
	 * 隐藏iframe框内容并显示加载提醒，加载完成后关闭提醒并显示iframe框新内容
	 */
	function iframe(url){
		var iframe = this.find('iframe'),
			loading = this.find('.loading');

		iframe.css({opacity: 0});

		iframe.attr('src', url);

		loading.show();

		iframe.load(function(){
			iframe.css({opacity: 1});
			loading.hide();
		});
	}

	/**
	 * 计算右侧内容区宽度
	 */
	function contentWidthCompute(index){
		if (!index){
			return false;
		}

		var layero = $('#layui-layer'+[index]),
			windowWidth = layero.outerWidth();

		layero.find('#iframe-main .content').css({
			width: (windowWidth - 218) + 'px'
		});
	}

	/**
	 * 检查是否进入全屏状态
	 * 
	 */
	function checkFullScreen(){
		//如果当前窗口高度大于或等于显示屏高度 - 40（底部任务栏高度，部分浏览器全屏状态不隐藏任务栏）的话，那么就视为已进入全屏状态。
		if ($(window).outerHeight() >= screenHeight - 40){
			displayTime();
			taskbarTime.show();
		}else{
			taskbarTime.hide();
			clearTimeout(taskbarTimeIndex);
		}
	}

	/**
	 * 显示右下角系统时间，10秒核对一下当前时间，减少内存消耗，理论上误差一定小于10秒。
	 */
	function displayTime(){
		var myDate = new Date(),
			hours = myDate.getHours(),
			minutes = myDate.getMinutes();

			hours = hours < 10 ? '0' + hours : hours;
			minutes = minutes < 10 ? '0' + minutes : minutes;

		$('#taskbar .taskbar-right .time p:eq(0)').html(hours + ':' + minutes);
		$('#taskbar .taskbar-right .time p:eq(1)').html(myDate.toLocaleDateString());

		taskbarTimeIndex = setTimeout(displayTime, 10000);
	}

	/**
	 * 打开新的iframe页面
	 */
	function openiframe(data){
		var content = laytpl($('#menu-tree').html()).render(data);

		return layer.open({
			type:1,
			area:['70%','80%'],
			maxmin: true,
			shift: 1,
			content: content,
			shade: 0,
			moveOut: true,
			scrollbar: false,
			zIndex: layer.zIndex, 
			fix: false,
			cancel: function(index){
				$('#taskbar li[data-index = ' + index + ']').remove();
			},
			success: function(layero, index){
				layer.style(index, {
					width: layero.outerWidth(),
					height: layero.outerHeight()
				});
				contentWidthCompute(index);
			},
			full: function(layero){
				setTimeout(function(){
					layero.find('.layui-layer-min').show();

					layero.css({
						height: ($(window).height() - 40) + 'px',
						'border-radius': 0
					});

					layero.find('.layui-layer-content').css({
						height: (layero.height() - layero.find('.layui-layer-title').outerHeight()) + 'px'
					});

					contentWidthCompute(layero.attr('times'));
				}, 150);
			},
			restore: function(layero){
				setTimeout(function(){
					layero.css({
						width: Math.ceil($(window).width() * .7) + 'px',
						height: Math.ceil($(window).height() * .8) + 'px',
						'border-radius': '10px'
					});

					layero.find('.layui-layer-content').css({
						height: (layero.height() - layero.find('.layui-layer-title').outerHeight()) + 'px'
					});

					contentWidthCompute(layero.attr('times'));
				}, 150);
			}
		});
	}

	/**
	 * 修改模块弹窗的标题，实现框架内页面切换标题跟随切换。
	 *
	 * @param  {Number} index layer弹窗的编号
	 * @param  {String} title 需要更换的标题内容
	 */
	function alterCurrentIframeTitle(index, title){
		var layero = $('#layui-layer' + index);
		if (layero.length === 1){
			layero.find('.layui-layer-title').html(title);
		}
	}

	/**
	 * 重新获取桌面图标
	 */
	function getDesktopIcon(){
		//异步获取
		
		//生成完毕后执行图标排列
		//sortDesktopIcon();
	}

	/**
	 * 生成右键菜单图标以及后边快捷键
	 */
	function createMenuIconAndkeyboard(sign, items){
		$.each(items, function(index) {
			var current = contentMenuItemsConfigure[index],
				keyboard = (this.name && current.keyboard) && '<span class="keyboard">' + current.keyboard + '</span>' || '';

			this.name && this.$node.html('<i class="fa fa-' + current.icon + '"></i><span>' + this.name + '</span>' + keyboard);
		});

		contentMenuSign[sign] = 1;
	}

	/**
	 * 检查当前右键菜单是否已生成图标和快捷方式（确保生成单例）
	 */
	function checkContentMenuSign(sign){
		var sign = sign.replace('.','');

		return contentMenuSign[sign] && true || sign;
	}

	/**
	 * 自适应高度及排列桌面图标
	 */
	function sortDesktopIcon(){
		var mainHeight = $(window).outerHeight() - 40; //当前页面的主体高度（排除底部高度）
			desktopColumnNum = Math.floor(mainHeight / 110), //计算以当前高度每列最大能存放多少个图标
			currentColumnNum = 1, currentOffset = [10, 10]; //初始化当前列图标数量以及下一个图标的偏移量

		//根据屏幕的实际尺寸修改桌面的高度
		$('#desktop-icon').css({
			height: mainHeight + 'px'
		});

		//循环当前的全部图标
		$.each($('#desktop-icon li'), function(index) {
			
			//当前列图标大于列允许存放最大值，将列数量重置，将偏移量高度重置、左侧右移，使其下一个图标的位置出现在第二列第一个。
			if (currentColumnNum > desktopColumnNum) {
				currentColumnNum = 1;
				currentOffset[0] = currentOffset[0] + 90;
				currentOffset[1] = 10;
			}

			//设置当前图标的偏移量
			$(this).css({
				left: currentOffset[0] + 'px',
				top: currentOffset[1] + 'px'
			});

			//递增当前列图标数量以及下一个图标的位置
			currentColumnNum++;
			currentOffset[1] += 110;
		});
	}
});