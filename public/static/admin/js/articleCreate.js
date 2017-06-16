define(['jquery', 'webuploader', 'messenger.future', 'remarkable', 'highlight', 'codemirror-gfm', 'eh.form', 'eh.xhr', 'layer', 'jquery.contextMenu', 'bootstrap', 'bootstrap-select-zh'], function($, WebUploader, Messenger, remarkable, hljs){
	var CodeMirror = require('../../lib/codemirror');
	var editor;

	window.importResource = function(data){
		if($.isArray(data) === true){
			var li = [];
			for (var i = 0; i < data.length; i++) {
				if ($('#resource-' + data[i].id).length == 0) {
					li.push('<li class="col-sm-2" id="resource-' + data[i].id + '" data-type="' + data[i].type + '"><div class="img"><img src="' + data[i].url + '" alt="' + data[i].name + '" /></div></li>');
				}
			}
			if (li.length > 0) {
				$('#resource-list ul').append(li.join(''));
				$('#resource-list').show();
				$('#resource-list li').height($('#resource-list ul').width() * 0.166667 - 10);
				layer.close(resoucerIframe);
			}
		}
	}

	var resoucerIframe = 0;
	var transitionEnd = 'transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd';
	var anchor = '';

	$(function(){
		var textareaHeight, previewHeight, validate,
			editorType = $('#editor-box').data('editor');

		//eh.htmlPreviewHeight();

		$('.selectpicker').selectpicker({
			size: 8,
			dropupAuto: false
		});

		$('.selectpicker').on('changed.bs.select', function(){
			var index = $(this).parents('.col-sm-5').index();
			if (index == 2) {
				eh.xhr.getCommon('/admin/article/getCategory.html', {'parent_id': $(this).val()}, {
					'parentObj': $('.selectpicker:eq(1)'),
					'tplID': 'category-tpl',
					'after': function(){
						$('.selectpicker:eq(1)').selectpicker('refresh');
					}
				});
			}

			$(this).parents('.form-group').find('#category-id').val($(this).val());
		});

		$('[data-toggle="tooltip"]').tooltip();

		if (editorType == 'markdown') {
			var markdownEditor = CodeMirror.fromTextArea(document.querySelector('#markdown'), {
				mode: 'gfm',
				lineWrapping: 'wrap'
			});

			markdownEditor.setSize('100%', '100%');

			markdownEditor.on('change', function(cm){
				$('#markdown').val(cm.getValue()).change();
			});

			var editorButton = {
				"h1": {
					"bindKey": {'win': "Ctrl-1", 'mac': "Cmd-1"},
					"exec": function (cm) {
						insert.call(cm, "# ", "", false);
					}
				},
				"h2": {
					"bindKey": {'win': "Ctrl-2", 'mac': "Cmd-2"},
					"exec": function (cm) {
						insert.call(cm, "## ", "", false);
					}
				},
				"h3": {
					"bindKey": {'win': "Ctrl-3", 'mac': 'Cmd-3'},
					"exec": function (cm) {
						insert.call(cm, "### ", "", false);
					}
				},
				"h4": {
					"bindKey": {'win': "Ctrl-4", 'mac': 'Cmd-4'},
					"exec": function (cm) {
						insert.call(cm, "#### ", "", false);
					}
				},
				"h5": {
					"bindKey": {'win': "Ctrl-5", 'mac': 'Cmd-5'},
					"exec": function (cm) {
						insert.call(cm, "##### ", "", false);
					}
				},
				"h6": {
					"bindKey": {'win': "Ctrl-6", 'mac': 'Cmd-6'},
					"exec": function (cm) {
						insert.call(cm, "###### ", "", false);
					}
				},
				"bold": {
					"bindKey": {'win': "Ctrl-B", 'mac': 'Cmd-B'},
					"exec": function (cm) {
						insert.call(cm, "**", "**", false);
					}
				},
				"italic": {
					"bindKey": {'win': "Ctrl-I", 'mac': 'Cmd-I'},
					"exec": function (cm) {
						insert.call(cm, "*", "*", false);
					}
				},
				"code": {
					"bindKey": {'win': "Ctrl-D", 'mac': 'Cmd-D'},
					"exec": function (cm) {
						var text = cm.getSelection(), cursor = cm.getCursor();

						if (text.length) {
							if (text.split("\n").length > 1) {
								insert.call(cm, "```\n", "\n```", false);
							} else {
								insert.call(cm, "`", "`", false);
							}
						} else {
							if (cursor.ch == 0) {
								insert.call(cm, "```\n", "\n```", true);
							} else {
								insert.call(cm, "`", "`", true);
							}
						}
					}
				},
				"ul": {
					"bindKey": {'win': "Ctrl-U", 'mac': 'Cmd-U'},
					"exec": function (cm) {
						insert.call(cm, "* ", "", true);
					}
				},
				"ol": {
					"bindKey": {'win': "Ctrl-O", 'mac': "Cmd-O"},
					"exec": function (cm) {
						insert.call(cm, "{$line}. ", "", true);
					}
				},
				"image": {
					"bindKey": {'win': "Ctrl-G", 'mac': "Cmd-G"},
					"exec": function (cm) {
						$('.resource-uploader label').click();
					}
				},
				"blockquote": {
					"bindKey": {'win': "Ctrl-Q", 'mac': 'Cmd-Q'},
					"exec": function (cm) {
						insert.call(cm, "> ", "", true);
					}
				},
				"hr": {
					"bindKey": {'win': "Ctrl-H", 'mac': 'Cmd-H'},
					"exec": function (cm) {
						insert.call(cm, "\n* * * * *\n", "", false);
					}
				},
				'resource': {
					"bindKey": {'win': "Ctrl-R", 'mac': 'Cmd-R'},
					"exec": function (cm) {
						resoucerIframe = layer.open({
							type:2,
							title: false,
							resize: false,
							move: false,
							area: ['100%', '100%'],
							closeBtn: false,
							content: '/admin/resource/index/iframe/1.html'
						});
					}
				}
			};

			var extraKeys = {};
			for (var i in editorButton) {
				extraKeys[editorButton[i].bindKey.win] = editorButton[i].exec;
			}

			markdownEditor.setOption("extraKeys", extraKeys);

			$('#markdown-button li').click(function(){
				var key = $(this).data('key');
				if (editorButton[key]) {
					editorButton[key].exec(markdownEditor);
				}else{
					switch (key){
						case 'toc':
						insert.call(markdownEditor, '[TOC]');
						break;
						case 'screen':
						var layero = parent.$('#layui-layer' + parent.$('#taskbar .list li.active').data('index'));
						if (layero.find('.layui-layer-maxmin').length == 0) {
							parent.$('#layui-layer' + parent.$('#taskbar .list li.active').data('index')).find('.layui-layer-max').click();
							setTimeout(function(){
								console.log($(window).height());
								$('#editor, .CodeMirror-code').height($(window).height()-50);
							}, 300);
						}else{
							$('#editor, .CodeMirror-code').height($(window).height()-50);
						}
						$(this).data('key', 'restore').find('i').removeClass('screen').addClass('restore');
						$('#editor-box').removeClass('col-sm-10').addClass('editor-box-screen');
						break;
						case 'restore':
						$('#editor-box').removeClass('editor-box-screen').addClass('col-sm-10');
						$(this).data('key', 'screen').find('i').removeClass('restore').addClass('screen');
						$('#editor, .CodeMirror-code').height(400);
						break;
						case 'code-mode':
						$('.form-markdown-left').css({
							width: '100%',
							'z-index': 3,
							'border-right': '1px solid #ccc'
						});
						$('.form-markdown-right').css({
							width: 0
						});
						$('.form-markdown-left').one(transitionEnd, function() {
							$('.form-markdown-left').css({
								'border': 'none'
							});
						});
						break;
						case 'column-mode':
						$('.form-markdown-left, .form-markdown-right').css({
							width: '50%',
							position: 'absolute'
						});
						$('.form-markdown-right').css({
							'border-left': '1px solid #ccc'
						});
						$('.form-markdown-left, .form-markdown-right').one(transitionEnd, function() {
							$('.form-markdown-left, .form-markdown-right').css({
								'z-index': 1
							});
							$('.form-markdown-left, .form-markdown-right').off(transitionEnd);
						});
						break;
						case 'preview-mode':
						$('.form-markdown-right').css({
							width: '100%',
							'z-index': 3
						});
						$('.form-markdown-right').one(transitionEnd, function() {
							$('.form-markdown-right').css({
								'border': 'none'
							});
						});
						break;
						default:
						layer.msg('暂不支持该按钮');
					}
				}
			});

			/**
			 * 实例化markdown解析器
			 */
			var md = new remarkable('full', {
				linkify: true,
				html: true,
				xhtmlOut: true,
				highlight: function(str, lang) {
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
			$('#markdown').change(function() {
				$('#html-preview .html-content').html(md.render(format($(this).val())));

				if (anchor) {
					$.each($('#html-preview .html-content').find(anchor), function(index, val) {
						$(this).attr('id', 'anchor' + index);
					});
				}
			});

			/**
			 * markdown滚动，预览内容跟随滚动，由于预览内容存在样式，一般情况下会比markdown页面要长，所有判断当前滚轮位置在markdown输入框的百分比，
			 * 预览内容同样滚动到该比例位置，基本可以保证一致性。
			 */
			$('.CodeMirror-vscrollbar').scroll(function() {
				textareaHeight = $(this).find('div').innerHeight();
				previewHeight = $('#html-preview .html-content').innerHeight();

				$('#html-preview').scrollTop($(this).scrollTop() * (previewHeight / textareaHeight));
			});

			
			editor = markdownEditor;
		}else{
			var ue = UE.getEditor('editorPlain', {
				UEDITOR_HOME_URL: '/static/lib/plugin/',
				serverUrl: '/ueditor/controller.php',
				autoHeightEnabled: true,
				autoFloatEnabled: true,
				topOffset: 76,
				initialFrameWidth: $('#editor-box').width() - 16,
				initialFrameHeight: 600,
				imageScaleEnabled: false,
				textarea: 'content',
				toolbars: [[
					'source', '|', 'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|', 'undo', 'redo', '|',
					'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
					'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
					'directionalityltr', 'directionalityrtl', 'indent', '|',
					'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
					'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
					'simpleupload', 'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'insertframe', 'insertcode', 'pagebreak', '|',
					'horizontal', 'date', 'time', 'spechars', 'wordimage', '|',
					'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
					'searchreplace', 'drafts'
				]],
				maximumWords: 20000
			});

			editor = ue;
		}
		

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
		 * 右键事件
		 */
		// $.contextMenu({
		// 	selector: '#markdown',
		// 	items: {
		// 		space: {
		// 			name: '<i class="fa fa-trash"></i>资源空间',
		// 			isHtmlName: true
		// 		}
		// 	},
		// 	callback: function(itemKey, opt){
		// 		switch (itemKey){
		// 			case 'space':
		// 				resoucerIframe = layer.open({
		// 					type:2,
		// 					title: false,
		// 					resize: false,
		// 					move: false,
		// 					area: ['100%', '100%'],
		// 					closeBtn: false,
		// 					content: '/admin/resource/index/iframe/1.html'
		// 				});
		// 				break;
		// 		}	
		// 	}
		// });

		/**
		 * 清空表单按钮点击事件绑定
		 */
		$('#header-button-empty-form').click(function(){
			eh.form.emptyFormData();
			$('#html-preview .html-content').html(md.render($(this).val()));
			$('.logo-uploader btn').html('上传缩略图');

			validate.resetForm();
			eh.form.validateHighlightRemove();
			markdownEditor.setValue('');
		});

		/**
		 * 保存表单按钮点击事件绑定
		 */
		$('#header-button-submit-form').click(function(){
			if (validate.form()){
				var option = {};

				if (editorType == 'markdown') {
					option = {
						extend : [
						{
							'name': 'content',
							'element': '.html-content'
						}
						]
					};
				}

				if ($('body').hasClass('original-action-edit')) {
					eh.xhr.messageRedirect('/admin/article/' + $('form').data('id') + '.html', eh.form.extractData(option), {submitType: 'put'});
				}else{
					eh.xhr.messageRedirect('/admin/article/.html', eh.form.extractData(option), {submitType: 'create'});
				}
			}else{
				eh.form.validateError(validate.errorMap);
			}
		});

		/**
		 * 键盘按下Tab时不要跳出，禁止掉默认功能。
		 */
		// $('#markdown').keydown(function(e) {
		// 	if (e.keyCode == 9) {
		// 		e.preventDefault();
		// 	}
		// });

		
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
		var resourceUploader = new WebUploader.Uploader({
			swf: '/static/lib/js/webuploader/Uploader.swf',
			server: '/admin/article/resource.html',
			pick: '.resource-uploader',
			accept: {
				title: 'Images',
				extensions: 'gif,jpg,jpeg,bmp,png',
				mimeTypes: 'image/*'
			},
			formData: {
				_ajax: 1
			},
			fileNumLimit: 1,
			auto: true,
			compress: false
		});

		/**
		 * 资源图片开始上传触发
		 */
		resourceUploader.on('startUpload', function(){
			eh.xhr.loadPrompt({'type': 'uploader'});
		});

		/**
		 * 资源图片上传成功后触发
		 */
		resourceUploader.on('uploadSuccess', function(file, response){
			eh.xhr.msgLayerIndex && layer.close(eh.xhr.msgLayerIndex);
			eh.xhr.msgLayerIndex = null;

			resourceUploader.reset();
			if (response.code == 1) {
				Messenger().post({
					message: '图片：' + file.name + ' 上传成功！',
					showCloseButton: true
				});

				var text = markdownEditor.getSelection(), alt;
				alt = text.length ? text : file.name;
				insert.call(markdownEditor, "![" + alt + "](" + response.data.url + ")");
			}else{
				Messenger().post({
					message: '图片：' + file.name + ' 上传失败！',
					showCloseButton: true,
					type: 'error'
				});
			}
		});

		/**
		 * 实例化资源上传组件并配置参数
		 */
		var uploader = new WebUploader.Uploader({
			swf: '/static/lib/js/webuploader/Uploader.swf',
			server: '/admin/article/resource.html',
			pick: '.logo-uploader',
			accept: {
				title: 'Images',
				extensions: 'gif,jpg,jpeg,bmp,png',
				mimeTypes: 'image/*'
			},
			formData: {
				_ajax: 1
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
				$('#thumbnail').val(file.name);
				$('#thumbnailUrl').val(response.data.url);
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

		$('#resource-list').on('click', 'li', function() {
			if ($(this).data('type') == 1) {
				var markdown = '![' + $(this).find('img').attr('alt') + '](' + $(this).find('img').attr('src') + ')';
			}

			$('#markdown').val($('#markdown').val() + markdown).keyup();
		});

		$(window).resize(function() {
			if ($('#resource-list li').length > 0) {
				$('#resource-list li').height($('#resource-list ul').width() * 0.166667 - 10);
			}


			$('.editor-box-screen #editor, .editor-box-screen .CodeMirror-code').height($(window).height()-50);
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
			anchor = '';
			return false;
		}

		anchor = [];

		for (var t = 0; t < tocs.length; t++){
			reg[t] = '#{' + tocs[t] + '}';
			anchor.push('h'+tocs[t]);
		}

		anchor = anchor.join(',');

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

			var string = result[i].replace(/\s*#+ ?/, '').replace(/ ?#+/, '');
		 		string = '[' + string + '](#anchor' + i + ')';
			if (currentIndex < tocIndex || (currentIndex == (tocIndex + 1) && tocLength > 0)) {
				tocIndex = currentIndex;
				tocLength = 1;
				resultStr += sign[tocIndex] + string + '\n';
			}else if (currentIndex == tocIndex) {
				tocLength++;
				resultStr += sign[tocIndex] + string + '\n';
			}
		}
		return str.replace(toc, resultStr);
	}

	function insert(text) {
		var selection = this.getSelection(),
		cursor = this.getCursor(),
		cursorCoords = this.cursorCoords(true, 'local'),
		scrollInfo = this.getScrollInfo(),
		start, end, _start, line = 0,
		length;
		this.focus();

		if (!(cursorCoords.top > scrollInfo.top && cursorCoords < scrollInfo.top + scrollInfo.clientHeight)) {
			this.scrollIntoView({line: cursor.line, ch: 0});
		}

        if (arguments.length > 1) { //首尾添加文本
        	start = arguments[0];
        	end = arguments[1];

            if (arguments[2]) { //按行添加


            	/* 插入数据 */
            	if (selection) {
            		selection = selection.split("\n");
            		length = selection.length;

            		/* 逐行添加 */
            		for (var i in selection) {
            			if (!length || $.trim(selection[i])) {
            				_start = start.replace("{$line}", ++line)
            				.replace("{$i}", i);
            				selection[i] = _start + selection[i] + end;
            			}
            		}

            		this.replaceSelection(selection.join("\n"), 'around');
            	} else {

            		_start = start.replace("{$line}", 1)
            		.replace("{$i}", 0);

            		this.replaceSelection(_start + end);
            	}

            } else {
            	if (selection) {
            		this.replaceSelection(start + selection + end, 'around');
            	} else {
            		this.replaceSelection(start, 'end');
            		this.replaceSelection(end, 'start');
            	}
            }
        } else { //插入文本
        	this.replaceSelection(text);
        } 
    }

    return {
    	editor: editor
    }
});