define(['jquery', 'webuploader', 'messenger.future'], function($, WebUploader, Messenger){

	$(function(){
		setFormHeight();

		$(window).resize(function(){
			setFormHeight();
		});

		Messenger.options = {
			extraClasses: 'messenger-fixed messenger-on-top',
			theme: 'future'
		};

		var uploader = new WebUploader.Uploader({
			swf: '/static/lib/js/webuploader/Uploader.swf',
			server: '/resource/Uploader/receiver',
			pick: '.logo-uploader',
			accept: {
				title: 'Images',
				extensions: 'gif,jpg,jpeg,bmp,png',
				mimeTypes: 'image/*'
			},
			fileNumLimit: 1,
			auto: true,
			compress: false,
			formData: {
				uploaderType: 'article'
			}
		});

		uploader.on('fileQueued', function(file){
			$('.webuploader-pick').next('div').hide();
			$('#thumbnail').val(file.name);
		});

		uploader.on('startUpload', function(file){
			$('.logo-uploader button').html('<i class="fa fa-spinner fa-pulse"></i> 正在上传');
		});

		uploader.on('uploadSuccess', function(file, response){
			uploader.reset();
			$('.webuploader-pick').next('div').show();
			Messenger().post({
				message: '图片：' + file.name + ' 上传成功！',
				showCloseButton: true
			});
			$('.logo-uploader button').html('<i class="fa fa-check"></i> 上传成功');
		});
	});

	function setFormHeight(){
		var listHeight = $(window).outerHeight() - $('header').outerHeight() - 10;
		$('section.form').height(listHeight);
	}
});