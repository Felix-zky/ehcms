define(['laytpl', 'jquery', 'eh'], function(){
	var data = {}, //声明

	data = {
		/**
		 * 渲染数据到模板
		 *
		 * @param {Object} option 参数对象集合
		 * 包含以下成员:
		 * tplID  {String} 需要渲染的模板
		 * data   {Object} 渲染的数据
		 * [view] {String} 视图容器的ID，默认为data-render
		 */
		render: function(option){

			if (renderParamCheck(option) !== true) {
				eh.debugPrint(eh.error);
				eh.error = '';
				return false;
			}

			laytpl($('#' + option.tplID).html()).render(option.data, function(result){
				$('#' + option.view || 'data-render').html(result);
			});
			
		}

		/**
		 * 数据双向绑定
		 */
		
		/**
		 * 双向数据修改器
		 */
	};

	/**
	 * 检查数据渲染参数
	 *
	 * @param  {Object}  option 参数对象集合
	 * @return {Boolean}        检查是否通过
	 */
	function renderParamCheck(option){

		//参数不是对象
		if (typeof option != 'object') {
			eh.error = '列表生成失败，参数必须为对象';
			return false;
		}

		//模板ID未设置或不是字符串或DOM不存在
		if (!option.tplID || typeof option.tplID != 'string' || $('#' + option.tplID).length !== 1) {
			eh.error('模板ID未设置或DOM不存在或格式不符');
			return false;
		}

		//数据不是对象
		if (!option.data || typeof option.data != 'object') {
			eh.error('渲染数据不存在或格式不符');
			return false;
		}

		//视图如果设置则验证
		if (option.view) {
			
		}

		return true;
	}

	eh.data = data;
});