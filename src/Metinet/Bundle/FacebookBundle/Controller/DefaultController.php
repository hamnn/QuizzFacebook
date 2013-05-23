<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends MetinetController {

    /**
     * @Route("/", name="accueil")
     * @Template()
     */
    public function indexAction() {
        
        $userRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:User');
	$quizzRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz');
        
        $quizzEnAvant = $quizzRepository->getPromotedQuizz(1);
        $derniersQuizz = $quizzRepository->getLastQuizz(4);
        $user = $this->getUserFromFacebookConnection();
        $classements = $userRepository->getUserRank($user);

        // récupération des ids sbook des friends de l'user
        $userFriends = $this->container->get('metinet.manager.fbuser')->getUserFriends($user->getFbUid());
        //Pour chaque utilisateur on extrait l'ID correspondant
        $friendsId = array();
        foreach($userFriends['data'] as $index => $friend){
            $friendsId[] = $friend['id'];
        }
        // classement de l'user par rapport à ses amis
        $titreClassement = "Classement parmi vos amis :";
        $arrayUserListing = $userRepository->getClassementUserAvecFriendsProches($user, $friendsId, 2);
        
        //si j'ai as d'mis et qe j'ai pas de curly
        if(count($arrayUserListing) < 2){ // < 2 car je suis dans l'arrayUserListing
            $titreClassement = "Classement parmi tous les joueurs :";
            $arrayUserListing = $userRepository->getClassementUserAvecUsersProches($user, 2);
        }
        
        
        return array(
            "quizzEnAvant"   => $quizzEnAvant,
            "derniersQuizz"   => $derniersQuizz,
            "classements"   => $classements,
            "arrayUserListing"   => $arrayUserListing,
            "titreClassement"   => $titreClassement
        );
    }

}
