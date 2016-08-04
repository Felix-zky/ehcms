define(['layer', 'jquery', 'eh', 'eh.validate.language'], function(dialog, $, eh, language){
	var validate = {}, //声明出口
		methods = {}, //验证方法合集
		language = language || {}; //获取提示语言包（语言包在require初始化时配置，也可以直接引入）

	//
	validata = {
		//函数调用入口
		check: function(param, data){
			var state = 1;

			//规则必须为数组
			if (typeof param.rules == 'object') {
				$.each(param.rules, function(index, val) {
					//获取当前规则的错误提示信息，没有为空对象。
					var message = typeof param.messages[index] == 'object' ? param.messages['index'] : {};

					//判断当前规则的条件是否为数组
					if (typeof val == 'object') {
						//验证数据是否存在
						var dataCheckResult = dataCheck(val, data[index]);

						//验证通过，继续执行
						if (dataCheckResult === true) {
							val.required && delete val.required;

							//当前规则为数组且需要验证的数据存在，循环执行各方法。
							$.each(val, function(mIndex, mVal) {
								var validataResult = typeof methods[mIndex] == 'function' && methods[mIndex](mVal);

								if (validataResult != true) {
									eh.debugPrint(message[mIndex] || language[mIndex] || language.default);
									state = 0;
									return false;
								}
							});

							//方法循环执行出错，直接全部终止。
							if (state == 0) {
								return false;
							}

						//返回false说明当前数据不存在，终止全部验证。	
						}else if (dataCheckResult === false) {
							state = 0;
							return false;
						}
					}else{
						return true;
					}
				});

				if (state == 0) {
					return false;
				}
			}else{
				return false;
			}
			return true;
		},
		//元素绑定入口
		bind: function(){

		}
	};

	/**
	 * 验证数据是否可用，如果验证方法中不包含required，那么有则验证，无则返回。如果包含required，那么数据必须存在。
	 * true: 表示数据验证通过
	 * false: 表示规则要求强制验证数据是否存在并且验证结果为错误
	 * null: 表示未要求强制验证数据且验证结果为错误
	 * false和null的区别在于：
	 * 如果要求强制验证却没验证通过，则整体返回false，终止全部验证；
	 * 如果未强制要求验证却没验证过，说明该数据属于有则验证，无则跳过，跳出对该数据验证，继续其他验证。
	 */
	function dataCheck(methods, data){
		var result = methods.required(data);

		if (methods.required == true && result !== true) {
			return false;
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
		required: function(data){
			// 0也是有效数据
			if (data == 0) {
				return false;
			}

			//过滤null、undefined、空数组、空对象
			if (!data || data == false || (typeof data == 'object' && $.isEmptyObject(data) == true)) {
				return false;
			}

			return true;
		},
		/** 判断数据类型 */
		type: function(value, data){
			return typeof data != value ? true : false;
		},
		/** 判断11位手机号 */
		phone: function
	};

	eh.validata = validata;

	return validata;
});