 jQuery.noConflict();
(function($) {
    $(function() {
	
		$("#cadreDescQuizz img").hover(
		  function () {
			recupIdClick(this);
		  },
		  function () {
				
		  });
		
		var idCss;
		function recupIdClick(o)
		{
			idCss = $(o).attr("id");
			//alert(idCss);	
			$('a#desc'+idCss).animate({top: '0px'}, 550, function() {});
			$('a h3#desc'+idCss).animate({top: '0px'}, 550, function() {});
		}
		
		$("#cadreDescQuizz a").hover(
		  function () {
			
		  },
		  function () {
			$('#cadreDescQuizz a').animate({top: '325px'}, 750, function() {});
			$('#cadreDescQuizz a h3').animate({top: '325px'}, 750, function() {});
		  });
                  
                  
                  
                  $("#cadreDesc4Quizz img").hover(
		  function () {
			recupIdClick(this);
		  },
		  function () {
				
		  });
		
		$("#cadreDesc4Quizz a").hover(
		  function () {
			
		  },
		  function () {
			$('#cadreDesc4Quizz a').animate({top: '325px'}, 750, function() {});
			$('#cadreDesc4Quizz a h3').animate({top: '325px'}, 750, function() {});
		  });
	
	})
})(jQuery)