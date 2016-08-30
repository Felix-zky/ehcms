define(['jquery'], function(){

	$(function(){
		setFormHeight();

		$(window).resize(function(){
			setFormHeight();
		});
	});

	function setFormHeight(){
		var listHeight = $(window).outerHeight() - $('header').outerHeight() - 10;
		$('section.form').height(listHeight);
	}
});