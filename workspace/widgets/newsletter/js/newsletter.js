;(function($, undefined){

	var W = {

		init: function(){
			this.$ = $('#newsletter');
			this.$.find('form').validate();
		}

	};

	$(document).ready(function(){
		W.init();
	});

})(jQuery);
