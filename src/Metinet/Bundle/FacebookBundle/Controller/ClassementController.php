<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ClassementController extends MetinetController {

    /**
     * @Route("/classement", name="classement")
     * @Template()
     */
    public function indexAction() {
        
	$userRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:User');        
        $user = $this->getUserFromFacebookConnection();
        
        $arrayUserListing = $userRepository->getClassementUserAvecAmis($user);
        
        $arrayTop10 = $userRepository->getTop10();
     
        return array(
            'arrayUserListing' => $arrayUserListing,
            'top10' => $arrayTop10
        );
    }

}
