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
		onQuizzEnd();
	    }
	}
	
	/**
	 * Fonction appelée lorsque l'user a répondu à une question.
	 * La fonction fait une requête AJAX en envoyant les données du formulaire de réponse à la question pour les enregistrer en BDD.
	 * @param form	Le formulaire qui a envoyé la / les réponse(s) de l'user pour la question en cours.
	 */
	enregistrerUserAnswer = function(form){
	    $.ajax({
		    type : 'POST',
		    url : $("#urlAjaxEnregistrerUserAnswer").val(),
		    dataType: 'json',
		    data: form.serialize(), // je sérialise les données du form, ici les $_POST
		    success : function(data) {
			return data.reponse;
		    }
		});
	}
	
	/**
	 * Fonction appelée lorsque le quizz démarre.
	 * La fonction va enregistrer la date de début du quizz.
	 */
	onQuizzStart = function(){
	    $.ajax({
		    type : 'POST',
		    url : $("#urlAjaxOnQuizzStart").val(),
		    dataType: 'json',
		    success : function(data) {
			return data.reponse;
		    }
		});
	}
	
	
	/**
	 * Fonction appelée lorsque le quizz se termine
	 */
	onQuizzEnd = function(){
	    $.ajax({
		    type : 'POST',
		    url : $("#urlAjaxOnQuizzEnd").val(),
		    dataType: 'json',
		    success : function(data) {
			$('#question').html(data.quizzEnd);
			$('#question').fadeIn("slow");
		    }
		});
	}
 
})(jQuery)