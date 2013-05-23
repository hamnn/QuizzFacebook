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
        
	$quizzResultRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:QuizzResult');
        
        $quizzResults = $quizzResultRepository->findBy(array(),array('winPoints' => 'DESC'));
        
        foreach($quizzResults as $oneResult){
            echo 'Id user : '.$oneResult->getId().' points : '.$oneResult->getWinPoints().'<br/><br/>';
        }
        exit;
        $session = $this->getRequest()->getSession();
        $user = $session->get('user');
        
        return array(
        );
    }

}
