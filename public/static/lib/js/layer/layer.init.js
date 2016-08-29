define(['layer.min'], function(){
	layer.config({
		path: '/static/lib/css/layer/',
		extend: ['skin/special/style.css'],
		skin: 'layer-ext-special'
	});

	return layer;
});