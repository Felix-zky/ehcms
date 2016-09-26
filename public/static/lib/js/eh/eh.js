/**
 * EH函数基础库，包含一些常用函数。
 *
 * @return {Object} eh 函数库对象
 */
define(['layer', 'jquery', 'laypage'], function(dialog){
	var eh = {}; //函数库名称

	eh = {

		debug : 0, //站点处于调试状态，所有eh函数内部错误（包括传值错误）都将以弹窗显性提示；正式上线需要将该参数设置为0，错误将在控制台输出。这并不影响函数内设置的与用户的交互提醒。
		error : '', //全局错误提醒容器，方便函数在返回false的同时，可以返回错误信息。尽量不要以该属性是否有空来判断是否有错误。

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
		 *
		 * @param {Object} option 分页参数
		 */
		 pageRender: function(option){
		 	option = $.extend({
		 		cont: 'list-pages', //容器。值支持id名、原生dom对象，jquery对象
				curr: location.hash.replace('#!page=', ''), //获取hash值为page的当前页，与hash参数要对应。
				hash: 'page', //自定义hash值
				skin: '#AF0000'
		 	},option);

			laypage(option);
		}
	};

	window.eh = eh;

	return eh;
});