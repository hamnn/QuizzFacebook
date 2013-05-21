/**
 * Lorsque l'on clique sur le lien commencer le quizz
 */
$("#startQuizzLink").click(function(){
    // affichage et mise en marche du chrono
    $("#chrono").fadeIn('slow');
    chrono();
    $(this).fadeOut('slow');
    // on enregistre la date de début du quizz
    onQuizzStart();
    // affiche la première question
    nextQuizzEvent();
});


/**
 * Lorsque l'on choisi une réponse
 */
$("#questionForm .answerRadio").change(function(){
    // on soumet le formulaire
    $(this).closest('form').submit();
});