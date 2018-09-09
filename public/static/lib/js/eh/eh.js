/**
 * EH函数基础库，包含一些常用函数。
 *
 * @return {Object} eh 函数库对象
 */
define(['layer', 'laypage', 'jquery'], function(dialog, laypage){
	var eh = {}; //函数库名称

	eh = {

		debug : 0, //站点处于调试状态，所有eh函数内部错误（包括传值错误）都将以弹窗显性提示；正式上线需要将该参数设置为0，错误将在控制台输出。这并不影响函数内设置的与用户的交互提醒。
		error : '', //全局错误提醒容器，方便函数在返回false的同时，可以返回错误信息。尽量不要以该属性是否有空来判断是否有错误。
		ds: '/', //目录分隔符
		suffix: '.html', //网站后缀，多用于后台异步生成地址使用，如果网站使用shtml后缀，可以直接修改该变量，但需要其他的后缀，请另行自定义，以免发生冲突。
		adminEdit: 'edit' + this.suffix, //用于服务端使用了资源路由且编辑页面需要同步跳转过去的情况下。

		/**
		 * 内部函数错误提醒
		 *
		 * @param {String} info 错误的内容
		 */
		debugPrint: function(info){
			if (this.debug == 1) {
				dialog.alert(info,{icon:2});
			}else{
				console.warn(info);
			}
		},

		/**
		 * 手机号码保护（隐藏中间4位数）
		 *
		 * @param  {Number} phone       手机号码
		 * @param  {String} replacement 号码中间的隐藏内容，默认是****。
		 * @return {Number}             替换后的手机号码
		 */
		phoneProtect: function(phone, replacement) {
			replacement = replacement || '****';

			if (phone && $.isNumeric(phone)) {
				var regexp = /^(\d{3})\d{4}(\d{4})$/;
				return phone.replace(regexp, "$1" + replacement + "$2");
			}
		},

		/**
		 * 后台iframe页面的内容区高度计算，除去头部。
		 */
		setIframeMainHeight: function(){
			$('.iframe-main').height($(window).outerHeight() - $('header').outerHeight(true));
		},

		/**
		 * 后台iframe页面内容区列表高度，目前统一命名：list-mian
		 * 减掉的80像素是分页的高度，在列表页需要预留出分页的位置
		 */
		setListHeight: function(){
			$('.list-main').height(($('.iframe-main').outerHeight() - 80));
		},

		/**
		 * 后台数据加载状态（默认水平+垂直居中）
		 */
		addLoadTips: function(pages, element){
			pages = pages || 1;
			element.length >= 1 && element.remove();
			var str = '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span style="font-size:18px; vertical-align:top;">第'+ pages +'页数据正在加载，请稍等！</span>';
			return layer.msg(str, {time: false});
		},

		/**
		 * 实时预览框与markdown输入框高度同步
		 */
		htmlPreviewHeight: function(htmlElement, markdownElement){
			htmlElement = (htmlElement && $(htmlElement).length > 0) || $('#html-preview');
			markdownElement = (markdownElement && $(markdownElement).length > 0) || $('#markdown');
			htmlElement.height(markdownElement.innerHeight());
		},

		/**
		 * 分页生成
		 * 默认采用hash做异步分页，可以使用option参数对默认值进行覆盖。
		 * 一般该函数仅做异步使用，同步跳转使用服务端创建。
		 *
		 * @param {Object} option 分页参数
		 */
		 pageRender: function(option){
		 	option = $.extend({
		 		cont: 'list-pages', //容器。值支持id名、原生dom对象，jquery对象
				skin: '#AF0000'
		 	},option);

			laypage(option);
		},

		/**
		 * 快捷设置并打开一个页面层，这个函数不是必用函数，只是设置了一些默认变量，方便一些而已。可以自行使用弹出层进行页面弹窗。
		 * 主要用于一些表单的填写，不允许页面滚动的情况下。
		 * 
		 * @param {Object} option 页面弹出层自定义参数
		 */
		openPage: function(option){
			defaultOption = {
				type: 1,
				scrollbar: false,
				shift: 1,
				shade: 0.5,
				btn: ['确定', '取消']
			},
			offsetRegExp = /^\d*(?=px$)|^auto$/; //偏移量设置规则

			//验证偏移量传参是否正确
			if (option.offset) {
				if (typeof offset == 'string') {
					if(offsetRegExp.exec(offset) == null){
						this.debugPrint('纵向（垂直）偏移量设置不正确。例：100px');
						return false;
					}
				}else if (typeof offset == 'object') {
					for (var i = 0; i < 2; i++){
						if(offsetRegExp.exec(offset[i]) == null){
							this.debugPrint(i == 0 ? '纵向（垂直）' : '横向（水平）' + '偏移量设置不正确。例：100px');
							return false;
						}
					}
				}
			}

			//弹出的内容必须指向DOM
			if (!option.content) {
				this.debugPrint('必须设置DOM！');
				return false;
			}

			//如果content为字符串则自动以ID类型获取它的对象并验证。
			if (typeof option.content == 'string') {
				option.content = $('#' + option.content);
				if (option.content.length == 0) {
					this.debugPrint('无法识别DOM！');
					return false;
				}
			}

			return layer.open($.extend(defaultOption, option));
		},

		/**
		 * 禁用右键
		 */
		noRightKey : function(){
			$(document).on('contextmenu', function(){
				return false;
			});
		},

		/**
		 * 关闭iframe的父页面的左侧列表
		 */
		closeParentSidebar: function(){
			var obj = parent.$('#' + window.frameElement.name);
			if (window.frameElement.name && obj.length == 1) {
				obj.find('#iframe-main .menu-tree').hide();
				obj.find('#iframe-main .content').width(obj.find('#iframe-main').width());
			}
		},
		
		/**
		 * 打开iframe的父页面的左侧列表
		 */
		openParentSidebar: function(){
			var obj = parent.$('#' + window.frameElement.name);
			if (window.frameElement.name && obj.length == 1) {
				obj.find('#iframe-main .menu-tree').show();
				obj.find('#iframe-main .content').width(obj.find('#iframe-main').width() - 218);
			}
		},

		/**
		 * 点击iframe的父页面左侧列表
		 */
		clickParentButton: function(key){
			var obj = parent.$('#' + window.frameElement.name);
			if (window.frameElement.name && obj.length == 1) {
				var key = obj.find('#iframe-main .level-2 li[data-key="' + key + '"]');
				var parentLi = key.parents('li');
				if (!parentLi.hasClass('selected')){
					parentLi.click();
				}
				key.click();
			}
		}
	};

	$.fn.extend({
		animateCss: function(animationName, callback) {
			var animationEnd = (function(el) {
				var animations = {
					animation: 'animationend',
					OAnimation: 'oAnimationEnd',
					MozAnimation: 'mozAnimationEnd',
					WebkitAnimation: 'webkitAnimationEnd',
				};

				for (var t in animations) {
					if (el.style[t] !== undefined) {
						return animations[t];
					}
				}
			})(document.createElement('div'));

			this.addClass('animated ' + animationName).one(animationEnd, function() {
				$(this).removeClass('animated ' + animationName);

				if (typeof callback === 'function') callback();
			});

			return this;
		}
	});

	window.eh = eh;
});