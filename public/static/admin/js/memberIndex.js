define(['layer', 'jquery', 'eh.xhr'], function(){
	
	$(function(){
		eh.setListHeight();
		eh.pageRender({
			pages: 50, 
			jump: function(obj, first){
				if (!first) {
					var index = eh.addLoadTips(obj.curr, $('.list-main li:gt(0)'));
					eh.xhr.get();
				}
			}
		});
	});
	
});