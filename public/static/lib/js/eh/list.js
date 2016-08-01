defind(['laypage', 'quickEdit', 'jquery', 'eh'], function(laypage, quickEdit){
	var exports = {},
		defaultOption = {

		};

	/**
	 * 渲染分页组件
	 *
	 * @param  {Object} option 分页配置，至少需要传递总页数参数，其他不传使用默认配置。
	 */
	exports.renderPage = function(option){
		option = (option && typeof option == 'function') ? $.extend(defaultOption, option) : eh.debugPrint('配置不能为空，至少需要传递总页数。');

		laypage(option);
	}

	eh.list = exports;

	return exports;
});