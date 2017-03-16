define(['layer.min'], function(){
	layer.config({
		path: '/static/lib/css/layer/',
		extend: ['special/style.css'],
		skin: 'layer-ext-special'
	});

	return layer;
});