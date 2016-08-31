define(['jquery', 'webuploader', 'messenger.future'], function($, WebUploader, Messenger){

	$(function(){
		setFormHeight();

		$(window).resize(function(){
			setFormHeight();
		});

		var uploader = new WebUploader.Uploader({
			swf: '/static/lib/js/webuploader/Uploader.swf',
			pick: $('.logo-uploader'),
		});
	});

	function setFormHeight(){
		var listHeight = $(window).outerHeight() - $('header').outerHeight() - 10;
		$('section.form').height(listHeight);
	}
});