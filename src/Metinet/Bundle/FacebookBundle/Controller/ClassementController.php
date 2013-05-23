<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

//use Metinet\Bundle\FacebookBundle\Entity\User;

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
        /*
        // on merge l'User en BDD pour enregistrer ses réponses au quizz
            $em = $this->getDoctrine()->getEntityManager();
            
            
        for($i = 0; $i < 100; $i++){
            $user = new User();
            $user->setFbUid($i);
            $user->setPicture($i.'.jpg');
            $user->setPoints($i * 10);
            $user->setLastName('last'.$i);
            $user->setFirstName('first'.$i);
            $em->persist($user);
        }
        $em->flush();
        die('ok');*/
        return array(
            'arrayUserListing' => $arrayUserListing,
            'top10' => $arrayTop10
        );
    }

}
