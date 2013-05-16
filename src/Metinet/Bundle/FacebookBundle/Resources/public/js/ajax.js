(function($){
 
	/**
	 * Fonction qui va faire une requete AJAX pour afficher la question N° nextQuestion
	 */
	afficherQuestion = function() {
	    // le numéro de la prochaine question
		$.ajax({
		    type : 'POST',
		    url : $("#urlAjaxQuestion").val(),
		    dataType: 'json',
		    success : function(data) {
			$('#question').html(data.question);
			$('#question').fadeIn("slow");
		    }
		});
	};
	
	/**
	 * Fonction appelée lorsque l'on clique sur commencer le quizz ou sur une validation de question
	 * et qui affiche la prochaine question ou qui fait le total des points du quizz si on est à la fin du quizz.
	 */
	nextQuizzAction = function() {
	    // si on n'est pas au bout du quizz, on affiche la prochaine question
	    if($("#nextQuestion").val() != ""){
		$('#question').fadeOut("slow");
		afficherQuestion();
	    }
	    // sinon on calcule les points du joueur pour ce quizz
	    else {
		;
	    }
	}
 
})(jQuery)