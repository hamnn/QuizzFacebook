<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Classe de controlleur générique au projet qui contient des fonctions utilisées dans plusieurs controlleurs du projet
 */
class MetinetController extends Controller {

    /**
     * Fonction qui retourne un onjet User correspondant à l'utilisateur qui joue au quizz avec sa connection Facebook
     * @return USER Un objet User
     */
    protected function getUserFromFacebookConnection() {
        // instanciation des repositories
        $userRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:User');
        // récupération de l'user à partir de sa connection sbook
        $userFbId = $this->container->get('metinet.manager.fbuser')->getUser();
        $userResult = $userRepository->findBy(array("fbUid" => $userFbId));
        $user = $userResult[0];
        return $user;
    }
    
}
