define(['layer', 'jquery', 'eh', 'eh.validate.language'], function(dialog, $, eh, language){
	var validate = {}, //声明出口
		methods = {}, //验证方法合集
		language = language || {}; //获取提示语言包（语言包在require初始化时配置，也可以直接引入）

	//
	validata = {
		//函数调用入口
		check: function(rules, data){
			var state = 1;

			$.each(rules, function(index, val) {
				var currentMethods = val.methods,
					dataCheckResult = dataCheck(currentMethods, data.index);

				if (dataCheckResult === true){
					if (typeof currentMethods == 'array') {
						$.each(currentMethods, function(mIndex, mVal) {
							methodCheck(mVal) && mVal(data.index, );
						});
					}else{
						methodCheck(currentMethods) && currentMethods(data.index, val.param);
					}
				}else if (dataCheckResult === false) {}{
					return false;
				}

				if (state == 0) {
					return false;
				}
			});
		},
		//元素绑定入口
		bind: function(){

		}
	};

	/**
	 * 验证方法检测，判断传入的方法类型、名称是否正确
	 *
	 * @param  {String} method 方法的名称
	 * @return {Boolean}       是否正确
	 */
	function methodCheck(method){

	}

	/**
	 * 验证数据是否可用，如果验证方法中不包含required，那么有则验证，无则返回。如果包含required，那么数据必须存在。
	 */
	function dataCheck(methods, data){
		var result = methods.required(data);

		if ((typeof currentMethods == 'array' && $.inArray('required', currentMethods)) || (typeof currentMethods == 'string' && currentMethods == 'required')) {
			if (result !== true){
				return false;
			}
		}else if (result !== true) {
			return null;
		}

		return true;
	}

	/**
	 * 验证方法合集
	 * 
	 * 各验证方法参数解释
	 * @param {Mixed}  value 需要验证的内容
	 * @param {Object} param 参数合集：
	 * 
	 * [rule]   {Mixed} 验证的规则
	 * [error]  {Mixed} 验证错误信息，它大部分情况下是字符串，但根据业务需要，它可能是其他类型。
	 */
	methods = {
		/** 判断是否存在 */
		required: function(value){

		},
		/** 判断数据类型 */
		type: function(value, param){
			if (typeof value != param.rule) {
				eh.error = param.error || language.type;
				return false;
			}
			return true;
		},
		/** 判断11位手机号 */
		phone: function
	};

	eh.validata = validata;

	return validata;
});