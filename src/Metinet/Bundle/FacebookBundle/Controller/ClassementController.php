<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response; // réponse JSON pour l'AJAX

class ClassementController extends MetinetController {

    /**
     * @Route("/classement", name="classement")
     * @Template()
     */
    public function indexAction() {
        
	$userRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:User');        
        $user = $this->getUserFromFacebookConnection();
        //+5/-5
        $arrayUserListing = $userRepository->getClassementUserAvecUsersProches($user, 5);
        
        
        //+top10
        $arrayTop10 = $userRepository->getTop10();
     
$pagination = $this->getAmisPagination();
        return array(
          'pagination' => $pagination,
            'arrayUserListing' => $arrayUserListing,
            'top10' => $arrayTop10
        );
    }
    
    
/**
* Fonction qui est appelée pour rendre la vue du classement des amis avec pagination en AJAX
* @Route("/classement/amisPagination", name="amisPagination")public function amisPaginationAction(){
* @Template("")
*/
public function amisPaginationAction(){
  
      $pagination = $this->getAmisPagination();
        // on génère la vue de la question à afficher
        $render = $this->renderView("MetinetFacebookBundle:Classement:amisPagination.html.twig",
	    array(  "pagination" => $pagination));
        // on retourne l'objet reponse AJAX contenant le json de la vue de la question à afficher
        return new Response(json_encode(array("reponse" => $render)));
     }
 
 
 /**
 * Fonction qui retourne l'objet pagination pour le classement des amis avec pagination
 */
 private function getAmisPagination(){
 //friends
        $session = $this->getRequest()->getSession();
        $user = $session->get('user');
        
        $userFriends = $this->container->get('metinet.manager.fbuser')->getUserFriends($user['fb_uid']);
        //Pour chaque utilisateur on extrait l'ID correspondant
        $friendsId = array();
        foreach($userFriends['data'] as $index => $friend){
            $friendsId[] = $friend['id'];
        }
$userRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:User');
     $queryFriends = $userRepository->getQueryAllFriends($friendsId);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $queryFriends,
            $this->get('request')->query->get('page', 1)/*page number*/,
            1/*limit per page*/
        );    
$pagination->setUsedRoute("amisPagination"); 
 return $pagination;
 }
}
