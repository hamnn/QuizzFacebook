/**
 * À la soumission du formulaire de réponse à une question du quizz, on vérifie si au moins une réponse a été cochée
 * et on affiche la prochaine question ou le calcul des points du quizz.
 */
$("#questionForm").submit(function(){
    // variable pour voir si le joueur a choisi au moins une réponse
    var hasAnswered = false;
    // récupération de toutes les checkboxes
    var checkboxes = $(this).find(":checkbox");
    // on parcours toutes les checkboxes
    checkboxes.each(function(){
	// si une checkbox est cochée, on met hasAnswered à true;
	if($(this).is(':checked')){
	    hasAnswered = true;
	}
    })
    
    // si le joueur a choisi au moins une réponse, on fait la prochaine action du quizz
    if(hasAnswered){
	nextQuizzEvent();
    }
    // si le joueur n'a choisi aucune réponse, on affiche le message d'erreur
    else{
	$("#errorQuestion").html('Vous devez choisir au moins une réponse');
	$("#errorQuestion").fadeIn('slow');
    }
    // on évite que le formulaire se soumette
    return false;
});