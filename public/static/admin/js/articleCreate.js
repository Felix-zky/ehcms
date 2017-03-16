define(['jquery', 'webuploader', 'messenger.future', 'remarkable', 'highlight', 'eh.form', 'eh.xhr', 'layer'], function($, WebUploader, Messenger, remarkable, hljs){

	$(function(){
		var textareaHeight, previewHeight, validate;

		eh.htmlPreviewHeight();

		/**
		 * 设置表单验证参数
		 */
		validate = eh.form.checkFormData({
			rules: {
				title: "required",
				markdown: "required"
			},
			messages: {
				title: '文章标题必须设置',
				markdown: 'markdown不能为空'
			}
		});

		/**
		 * 清空表单按钮点击事件绑定
		 */
		$('#header-button-empty-form').click(function(){
			eh.form.emptyFormData();
			$('#html-preview .html-content').html(md.render($(this).val()));
			$('.logo-uploader btn').html('上传缩略图');

			validate.resetForm();
			eh.form.validateHighlightRemove();
		});

		/**
		 * 保存表单按钮点击事件绑定
		 */
		$('#header-button-submit-form').click(function(){
			if (validate.form()){
				option = {
					except: ['keywords'],
					extend : [
						{
							'name': 'content',
							'element': '.html-content'
						}
					]
				};
				//console.log(eh.form.extractData(option));
				var done = {
					fail: function(data){
						//$('.iframe-main').prepend(data.title);
						console.log(data);
					}
				}
				eh.xhr.post('/article/index', eh.form.extractData(option), '', done);
			}else{
				eh.form.validateError(validate.errorMap);
			}
		});

		/**
		 * 实例化markdown解析器
		 */
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

		/**
		 * 键盘按钮抬起立即解析markdown标记并生成新的预览内容
		 */
		$('#markdown').keyup(function(){
			$('#html-preview .html-content').html(md.render(format($(this).val())));
			textareaHeight = this.scrollHeight,
			previewHeight = $('#html-preview .html-content').innerHeight();
		});

		/**
		 * markdown滚动，预览内容跟随滚动，由于预览内容存在样式，一般情况下会比markdown页面要长，所有判断当前滚轮位置在markdown输入框的百分比，
		 * 预览内容同样滚动到该比例位置，基本可以保证一致性。
		 */
		$('#markdown').scroll(function() {
			if (previewHeight > 0 && textareaHeight > 0) {
				if ($(this).scrollTop() + $(this).innerHeight() == textareaHeight) {
					$('#html-preview').scrollTop(previewHeight - $(this).outerHeight());
				}else{
					$('#html-preview').scrollTop($(this).scrollTop() * (previewHeight/textareaHeight));
				}
			}
		});

		/**
		 * 配置messenger的显示位置及样式
		 */
		Messenger.options = {
			extraClasses: 'messenger-fixed messenger-on-top',
			theme: 'future'
		};

		/**
		 * 实例化资源上传组件并配置参数
		 */
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

		/**
		 * 缩略图进入列队触发
		 */
		uploader.on('fileQueued', function(file){
			$('.webuploader-pick').next('div').hide();
			$('#thumbnail').val(file.name);
			uploader.option('formData', {
				extension: file.ext
			})
		});

		/**
		 * 缩略图开始上传触发
		 */
		uploader.on('startUpload', function(file){
			$('.logo-uploader button').html('<i class="fa fa-spinner fa-pulse"></i> 正在上传');
		});

		/**
		 * 缩略图上传成功后触发
		 */
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

	function format(str){
		var tocResult = toc(str);
		if (!!tocResult == true) {
			str = tocResult;
		}
		return str;
	}

	function toc(str){
		if (!str) {
			return false;
		}

		var tocReg = /\[TOC[=\d,]*\]/,
		tocIndex = 0,
		tocLength = 0,
		sign = ['+ ', '  - ', '    * ', '      * ', '        * ', '          * '],
		toc,
		tocs = new Array(),
		newTocs = new Array(),
		reg = new Array(),
		result = null,
		resultStr = '';

		toc = tocReg.exec(str);
		if (toc == null) {
			return false;
		}

		toc = toc[0];

		if (toc == '[TOC]') {
			tocs = [1,2,3,4,5,6];
		}else if (/=/.test(toc)) {
			tocs = toc.replace(/\[TOC=?/, '').replace(']', '').split(',').sort();
		}
		if (!tocs[0]) {
			return false;
		}
		for (var t = 0; t < tocs.length; t++){
			reg[t] = '#{' + tocs[t] + '}';
		}
		reg = reg.join('|');
		reg = new RegExp("(\\s|^)(" + reg + ") .*","g");

		result = str.match(reg);
		if (result == null) {
			return false;
		}

		for (var t = 0; t < tocs.length; t++){
			for (var r = 0; r < result.length; r++){
				if (/#+ /g.exec(result[r])[0].replace(' ', '').split('').length == tocs[t]){
					newTocs[newTocs.length] = tocs[t];
					break;
				}
			}
		}

		tocs = newTocs;

		for (var i = 0; i < result.length; i++){
			var currentIndex = -1;

			for (var t = 0; t < tocs.length; t++){
				if (/#+ /g.exec(result[i])[0].replace(' ', '').split('').length == tocs[t]){
					currentIndex = t;
					break;
				}
			}

			if (currentIndex == -1) {
				continue;
			}

			if (currentIndex < tocIndex || (currentIndex == (tocIndex + 1) && tocLength > 0)) {
				tocIndex = currentIndex;
				tocLength = 1;
				resultStr += sign[tocIndex] + result[i].replace(/\s*#+ ?/, '').replace(/ ?#+/, '') + '\n';
			}else if (currentIndex == tocIndex) {
				tocLength++;
				resultStr += sign[tocIndex] + result[i].replace(/\s*#+ ?/, '').replace(/ ?#+/, '') + '\n';
			}
		}
		return str.replace(toc, resultStr);
	}
});