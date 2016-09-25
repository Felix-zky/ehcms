define(['jquery', 'eh.xhr'], function(){

	$(function(){
		var animate = [
			'fadeIn',
			'zoomIn',
			'zoomInLeft',
			'zoomInRight',
			'zoomInDown',
			'zoomInUp'
		];

		$('section.login').css({
			'animation-name': animate[Math.floor(Math.random()*6)],
		});
	});
});