 jQuery.noConflict();
(function($) {
    $(function() {
	
		$(".hover").hover(
		  function () {
			recupIdClick(this);
		  },
		  function () {
			$('#cadreDescQuizz a').animate({top: '210px'}, 500, function() {});
                        $('#cadreDesc4Quizz a').animate({top: '190px'}, 500, function() {});
		  });
		
		var idCss;
		function recupIdClick(o)
		{
			idCss = $(o).attr("id");
			//alert(idCss);	
			$('a#desc'+idCss).animate({top: '0px'}, 500, function() {});
		}
	
	})
})(jQuery)