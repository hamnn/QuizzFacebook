(function($){
 
	/**
	 * Fonction qui va faire une requete AJAX pour afficher la question N° nextQuestion
	 */
	afficherQuestion = function() {
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
	nextQuizzEvent = function() {
	    // si on n'est pas au bout du quizz, on affiche la prochaine question
	    if(parseInt($("#nextQuestion").val()) >= 0){
		$('#question').fadeOut("slow");
		afficherQuestion();
	    }
	    // sinon on calcule les points du joueur pour ce quizz
	    else {
		// on arrête le chronomètre
		stopChrono();
		$('#question').fadeOut("slow");
	    }
	}
        
        
        // Modification en ajax du champs texte answer
        $(".texte_edit").editable($("#aa").val(), {
            id : 'id',
            name : 'title',
            select : true,
            onblur: "submit",
            cssclass : "ajaxedit",
            width: '100%',
            placeholder: "Cliquer pour modifier...",
            tooltip : "Cliquer pour modifier..."
        }); 
 
})(jQuery)