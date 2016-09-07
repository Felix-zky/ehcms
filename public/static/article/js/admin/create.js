define(['jquery', 'webuploader', 'messenger.future', 'remarkable', 'highlight', 'eh.form'], function($, WebUploader, Messenger, remarkable, hljs){

	$(function(){
		var textareaHeight, previewHeight;

		setFormHeight();
		htmlPreviewHeight();

		$(window).resize(function(){
			setFormHeight();
		});

		$('#header-button-empty-form').click(function(){
			eh.form.emptyForm();
			$('#html-preview .html-content').html(md.render($(this).val()));
		});

		eh.form.checkFormData('form', {
			rules: {
				title: "required"
			},
			messages: {
				title: '文章标题必须设置'
			}
		});

		$('#header-button-submit-form').click(function(){
			$('form').submit();
		});

		var md = new remarkable('full', {
			linkify: true,
			linkTarget: 'blank',
			highlight: function (str, lang) {
				if (lang && hljs.getLanguage(lang)) {
					try {
						return hljs.highlight(lang, str).value;
					} catch (err) {}
				}

				try {
					return hljs.highlightAuto(str).value;
				} catch (err) {}

				return '';
			}
		});

		$('#markdown').keyup(function(){
			$('#html-preview .html-content').html(md.render($(this).val()));
			textareaHeight = this.scrollHeight,
			previewHeight = $('#html-preview .html-content').innerHeight();
		});

		$('#markdown').scroll(function() {
			if (previewHeight > 0 && textareaHeight > 0) {
				if ($(this).scrollTop() + $(this).innerHeight() == textareaHeight) {
					$('#html-preview').scrollTop(previewHeight - $(this).outerHeight());
				}else{
					$('#html-preview').scrollTop($(this).scrollTop() * (previewHeight/textareaHeight));
				}
			}
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
			compress: false
		});

		uploader.on('fileQueued', function(file){
			$('.webuploader-pick').next('div').hide();
			$('#thumbnail').val(file.name);
			uploader.option('formData', {
				extension: file.ext
			})
		});

		uploader.on('startUpload', function(file){
			$('.logo-uploader button').html('<i class="fa fa-spinner fa-pulse"></i> 正在上传');
		});

		uploader.on('uploadSuccess', function(file, response){
			uploader.reset();
			$('.webuploader-pick').next('div').show();

			if (response.code == 1) {
				Messenger().post({
					message: '图片：' + file.name + ' 上传成功！',
					showCloseButton: true
				});
				$('.logo-uploader button').html('<i class="fa fa-check"></i> 上传成功');
				$('#thumbnail').val(file.name + '（' + response.data.file_name + '）');
			}else{
				Messenger().post({
					message: '图片：' + file.name + ' 上传失败！',
					showCloseButton: true,
					type: 'error'
				});
				$('.logo-uploader button').html('重新上传');
				$('#thumbnail').val('');
			}
		});
	});

	function setFormHeight(){
		var listHeight = $(window).outerHeight() - $('header').outerHeight() - 10;
		$('section.form').height(listHeight);
	}

	function htmlPreviewHeight(){
		$('#html-preview').height($('#markdown').innerHeight());
	}
});