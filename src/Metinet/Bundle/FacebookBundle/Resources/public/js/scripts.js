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


/**
 * Lorsque l'on souhaite ajouter une reponse
 */
$("#addanswer").click(function(){
    //Si l'element n'existe pas alors on affiche pas
    //Pour ajouter qu'une seule réponse la fois
    if($('.answercheck').length == 0){
        AjaxAddAnswer();
    }
    return false;
});

    //Initialisation de la variable qui récupère le lien
    var theHREF;
 
    //Fonction d'affichage du pop up'
    $( "#dialog-confirm" ).dialog({
        resizable: false,
        height:160,
        width:550,
        autoOpen: false,
        modal: true,
        buttons: {
            "Oui": function() {
                $( this ).dialog( "close" );
                window.location.href = theHREF;
            },
            "Annuler": function() {
                $( this ).dialog( "close" );
            }
        }
    });            
 
    //Si on souhaite supprimer
    $("a.checkdelete").click(function(e) {
        e.preventDefault();
        //On récupère le lien
        theHREF = $(this).attr("href");
        //On entre dans la fonction
        $("#dialog-confirm").dialog("open");
    });


