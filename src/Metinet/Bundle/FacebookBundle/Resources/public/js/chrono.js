var centi=0; // initialise les dixtièmes
var secon=0; //initialise les secondes
var minu=0; //initialise les minutes

function chrono(){
    centi++; //incrémentation des dixièmes de 1
    if (centi>9){centi=0;secon++;} //si les dixièmes > 9, on les réinitialise à 0 et on incrémente les secondes de 1
    if (secon>59){secon=0;minu++;} //si les secondes > 59, on les réinitialise à 0 et on incrémente les minutes de 1
    $("#chrono #dec").html(centi); //on affiche les dixièmes
    $("#chrono #sec").html(secon); //on affiche les secondes
    $("#chrono #min").html(minu); //on affiche les minutes
    compte=setTimeout('chrono()',100); //la fonction est relancée tous les 10° de secondes
}

function stopChrono(){ //fonction qui arrête le chrono
    clearTimeout(compte); //arrête la fonction chrono()
}

$("#startQuizzLink").click(function(){
    // affichage et mise en marche du chrono
    $("#chrono").fadeIn('slow');
    chrono();
    $(this).fadeOut('slow');
    // affiche la première question
    nextQuizzEvent();
});