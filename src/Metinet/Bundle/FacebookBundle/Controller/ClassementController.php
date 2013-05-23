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
        
        
        //+5/-5
        $arrayUserListing = $userRepository->getClassementUserAvecAmis($user);        
        
        
        //friends
        $session = $this->getRequest()->getSession();
        $user = $session->get('user');
        
        $userFriends = $this->container->get('metinet.manager.fbuser')->getUserFriends($user['fb_uid']);
        //Pour chaque utilisateur on extrait l'ID correspondant
        $friendsId = array();
        foreach($userFriends['data'] as $index => $friend){
            $friendsId[] = $friend['id'];
        }
        
        $queryFriends = $userRepository->getQueryAllFriends($friendsId);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $queryFriends,
            $this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );       
        
        
        //+top10
        $arrayTop10 = $userRepository->getTop10();
        
        
     
        return array(
            'pagination' => $pagination,
            'arrayUserListing' => $arrayUserListing,
            'top10' => $arrayTop10
        );
    }
    
    

}
