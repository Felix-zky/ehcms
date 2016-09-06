define(['jquery', 'eh'], function(){

	var form = {
		emptyForm: function(element){
			var obj = (element && $(element).length > 0) ? $(element) : $('form');

			obj.find('input[type="text"], textarea').val('');
		}
	}

	eh.form = form;

});