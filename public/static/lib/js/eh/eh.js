/**
 * EH函数基础库，包含一些常用函数。
 *
 * @return {Object} eh 函数库对象
 */
define(['layer', 'jquery'], function(dialog){
	var debug = 1, //站点处于调试状态，所有eh函数内部错误（包括传值错误）都将以弹窗显性提示；正式上线需要将该参数设置为0，错误将在控制台输出。这并不影响函数内设置的与用户的交互提醒。
		eh = {}; //函数库名称

	eh = {

		error : ''; //全局错误提醒容器，方便在函数在返回false的同时，可以返回错误信息。每次使用完毕记得赋空值，尽量不要以该属性是否有空来判断是否有错误。

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
		}
	};

	window.eh = eh;

	return eh;
});