jQuery.noConflict();
(function($) {
    $(function() {
        var ie = document.all ? true : false // On test le navigateur pour IE 
        onload = preloadImage // Appel de la function preloadImage() au chargement de la page. Peut etre remplacer par <body onload="preloadImage()">

        function addZero(chiffre) { // Function qui ajoute un zero devant les chiffres
            if (chiffre < 10) {
                chiffre = chiffre;
            }
            if (chiffre < 100) {
                chiffre = chiffre;
            }
            return chiffre;
        }
        var i = 0; // On initialise le comptage des images a 0.
        function preloadImage() {
            if (!ie) {

                var myContent = document.getElementsByTagName("img") // On repere les images contenu dans la page
                var totalImage = myContent.length // Et on compte combien il y'en a
                percent = Math.ceil((i + 1) * 100 / totalImage) // A chaque passage dans la fonction, on calcule le pourcentage de i par rapport au nb d'image

                if (i < totalImage) { // Si l'image traité n'est pas la derniere image du doc on execute ce qui suit.
                    if (myContent.item(i).complete) {  // si l'image i est chargée, on envoi le pourcentage dans le div
                        document.getElementById("preloadPercent").innerHTML = addZero(percent) + "%";
                        i++ // On increment i, pour passer a l'image suivante
                        setTimeout(preloadImage) // Et hop, on repasse dans la fonction avec l'image suivante
                    }
                } else { // Sinon, on deduit que les images ont toute été chargées, et on cache le preload.
                    //if (!ie) document.getElementById("preload").style.visibility = "hidden"; // Si le client n'est pas IE on vire tout simplement le cache
                    $("#preload").delay(400).fadeOut('slow');
                }
            } else {
                document.getElementById("preload").style.visibility = "hidden";
            }
        }

        var opacity = 100 // Opacity de depart
        function crosoftFade() {
            opacity -= 25; // Opacity -25 a chaque passage
            document.getElementById("preload").style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=" + opacity + ")";
            if (opacity > 0)
                setTimeout(crosoftFade); // On passe cette fonction en boucle tant que le cache n'a pas disparu
            else
                document.getElementById("preload").style.visibility = "hidden";
        }
    })
})(jQuery)
