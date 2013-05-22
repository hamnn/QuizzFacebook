 jQuery.noConflict();
(function($) {
    $(function() {
			var cpt = 0;
                        $('.navigation li ul').show();
		 // On cache les sous-menus :
			$(".navigation ul.subMenu").hide();
			// On sélectionne tous les items de liste portant la classe "toggleSubMenu"
			// et on remplace l'élément span qu'ils contiennent par un lien :
			$(".navigation li.toggleSubMenu span").each( function () {
				// On stocke le contenu du span :
				cpt++;
				var TexteSpan = $(this).text();
				$(this).replaceWith('<a id="menu'+cpt+'" href="" title="Afficher la catégorie">' + TexteSpan + '<p class="closeMenu" id="closemenu'+cpt+'">&#9650;</p><p class="openMenu" id="openmenu'+cpt+'">&#9660;</p><\/a>') ;
			});

			// On modifie l'évènement "click" sur les liens dans les items de liste
			// qui portent la classe "toggleSubMenu" :
			$(".navigation li.toggleSubMenu > a").click( function () {
				// Si le sous-menu était déjà ouvert, on le referme :
				if ($(this).next("ul.subMenu:visible").length != 0) {
					$(this).delay(500).next("ul.subMenu").slideUp("normal", function () { $(this).parent().removeClass("open") });
				}
				// Si le sous-menu est caché, on ferme les autres et on l'affiche :
				else {
					$(".navigation ul.subMenu").slideUp("normal", function () { $(this).parent().removeClass("open") });
					$(this).next("ul.subMenu").slideDown("normal", function () { $(this).parent().addClass("open") });
                                        $(".imgQuizz").delay(750).fadeIn('slow');
				}
				// On empêche le navigateur de suivre le lien :
				return false;
			});			
    })
})(jQuery)