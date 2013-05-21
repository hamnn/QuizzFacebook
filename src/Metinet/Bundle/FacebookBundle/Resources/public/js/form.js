/**
 * À la soumission du formulaire de réponse à une question du quizz, on vérifie si au moins une réponse a été cochée
 * et on affiche la prochaine question ou le calcul des points du quizz.
 */
$("#questionForm").submit(function(){
    // variable pour voir si le joueur a choisi au moins une réponse
    var hasAnswered = false;
    // récupération de tous les radio buttons
    var radios = $(this).find(":radio");
    // on parcours tous les radio buttons
    radios.each(function(){
	// si un radio button est coché, on met hasAnswered à true;
	if($(this).is(':checked')){
	    hasAnswered = true;
	}
    })
    
    // si le joueur a choisi une réponse, on enregistre sa réponse et on fait la prochaine action du quizz
    if(hasAnswered){
	enregistrerUserAnswer($(this));
	nextQuizzEvent();
    }
    // si le joueur n'a choisi aucune réponse, on affiche le message d'erreur
    else{
	$("#errorQuestion").html('Vous devez choisir une réponse');
	$("#errorQuestion").fadeIn('slow');
    }
    // on évite que le formulaire se soumette lui même
    return false;
});