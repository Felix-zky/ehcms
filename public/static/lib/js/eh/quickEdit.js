/**
 * 快速编辑（目前主要用于表格和列表）
 *
 * @return {Object} 快速编辑功能集合
 */
define(['layer', 'jquery', 'eh', 'eh.xhr'], function(dialog){
	var quickEdit = {};

	eh.quickEdit = quickEdit;

	return quickEdit;
});