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

        $arrayUserListing = $userRepository->getClassementUserAvecAmis($user);
        
        
        return array(
            "quizzEnAvant"   => $quizzEnAvant,
            "derniersQuizz"   => $derniersQuizz,
            "classements"   => $classements,
            "arrayUserListing"   => $arrayUserListing
        );
    }

}
