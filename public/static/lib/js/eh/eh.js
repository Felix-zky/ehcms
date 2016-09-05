/**
 * EH函数基础库，包含一些常用函数。
 *
 * @return {Object} eh 函数库对象
 */
define(['layer', 'jquery'], function(dialog){
	var debug = 1, //站点处于调试状态，所有eh函数内部错误（包括传值错误）都将以弹窗显性提示；正式上线需要将该参数设置为0，错误将在控制台输出。这并不影响函数内设置的与用户的交互提醒。
		eh = {}; //函数库名称

	eh = {

		error : '', //全局错误提醒容器，方便在函数在返回false的同时，可以返回错误信息。每次使用完毕记得赋空值，尽量不要以该属性是否有空来判断是否有错误。

		/**
		 * 内部函数错误提醒
		 *
		 * @param {String} info 错误的内容
		 */
		debugPrint: function(info){
			if (debug == 1) {
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
		 * 页面按钮函数绑定
		 * 使用该函数绑定按钮，有效的防止默认按钮函数被重载，被覆盖等问题，将函数私有化，内部调用。
		 * @param  {Object}  option 当前页面需要注册的按钮或按钮数组
		 * @return {Boolean}        页面元素是否挂载成功
		 */
		buttonBind: function(option){
			var that = this;

			if ($.isArray(option) !== true) {
				that.debugPrint('执行页面按钮绑定时，参数必须为数组类型！');
				return false;
			}else if (option == false) {
				that.debugPrint('执行页面按钮绑定时，参数不能为空数组！');
				return false;
			}

			$.each(option, function(index, val) {
				if (!val.element) {
					that.debugPrint('按钮的元素不能为空，可以设置id也可以设置class！');
					return false;
				}

				var bindFunction = val.bindFunction || val.element.replace(/^\.|^\#/, '');

				if ($.isFunction(window[bindFunction]) !== true){
					that.debugPrint('设置的绑定函数不存在！');
					return false;
				}else{
					$(val.element).click(function(){
						val.beforeFunction && that.functionExecute.call(val, val.beforeFunction);
						window[bindFunction].call(val);
						val.afterFunction && that.functionExecute.call(val, val.afterFunction);
					});
				}
			});
		},

		/**
		 * 函数执行，支持单函数，对象或数组多函数。
		 * 一般使用在前端钩子上，因为传递过来的函数不清楚是多个还是单个，直接调用该函数即可
		 * 如果使用call调用该函数，作用域将被延续传递给执行函数，如果未使用call调用该函数，作用域将指向EH全局对象。
		 */
		functionExecute: function(functions){
			var type = typeof functions,
				that = this;

			if (type == 'function') {
				functions.call(that);
			}else if (($.isArray(functions) && functions == true) || (type == 'object' && $.isEmptyObject(functions) == false)) {
				$.each(functions, function() {
					this.call(that);
				});
			}
		}
	};

	window.eh = eh;

	return eh;
});