define(['laypage', 'laytpl', 'jquery', 'eh'], function(laypage, laytpl){
	var list = {}, //声明
		defaultPageOption = { //默认分页生成参数
			cont: 'page-list',
			hash: 'page'
		};

	list = {
		/**
		 * 渲染列表组件
		 *
		 * @param {Object} option 参数对象集合
		 * 包含以下成员:
		 * tplID  {String} 需要渲染的模板
		 * data   {Object} 渲染的数据
		 * [view] {String} 视图容器的ID，默认为lists
		 */
		renderList : function(option){

			if (listParamCheck(option) !== true) {
				eh.debugPrint(eh.error);
				eh.error = '';
				return false;
			}

			laytpl($('#' + option.tplID).html()).render(option.data, function(result){

			});
		},

		/**
		 * 渲染分页组件
		 *
		 * @param  {Object} option 分页配置，至少需要传递总页数参数，其他不传使用默认配置。
		 */
		list.renderPage = function(option){
			option = (option && typeof option == 'object') ? $.extend(defaultPageOption, option) : eh.debugPrint('配置不能为空，至少需要传递总页数。');

			laypage(option);
		};
	};

	/**
	 * 列表参数检查
	 *
	 * @param  {Object}  option 参数对象集合
	 * @return {Boolean}        检查是否通过
	 */
	function listParamCheck(option){

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

	eh.list = list;

	return list;
});