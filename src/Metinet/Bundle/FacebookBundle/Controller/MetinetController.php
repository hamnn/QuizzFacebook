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
    
    
    /**
     * Fonction callback appelée pour classer les joueurs en fonction de leur score DESC puis de leur temps de réponse
     * au quizz DESC.
     * La fonction est appelée dans les controlleurs avec la fonction PHP usort.
     * Ex : usort($arrayUsersATrier, array($this, "sortUsersByScoreDESCAndByAnsweringTimeDESC"));
     * @link http://php.net/manual/fr/function.usort.php
     * @param USER $userA
     * @param USER $userB
     * @return INT 1, 0 ou -1 en fonction que l'user A soit respectivement mieux classé, à égalité ou moins bien classé que l'user B
     */
    protected static function sortUsersByScoreDESCAndByAnsweringTimeDESC($userA, $userB){
	// si les deux users ont le même score
	if($userA->getWinPointsForThisQuizz() == $userB->getWinPointsForThisQuizz()){
	    // si l'user A a répondu plus vite
	    if($userA->getTimeForThisQuizz() < $userB->getTimeForThisQuizz()){
		return -1; // l'user A est mieux classé que l'user B
	    }
	    // si l'user B a répondu plus vite
	    elseif($userA->getTimeForThisQuizz() > $userB->getTimeForThisQuizz()){
		return 1; // l'user B est mieux classé que l'user A
	    }
	    // les deux users ont le même temps de réponse
	    else{
		return 0; // les users A et B sont égaux
	    }
	}
	// si l'user A a un meilleur score que l'user B pour ce quizz
	else if($userA->getWinPointsForThisQuizz() > $userB->getWinPointsForThisQuizz()){
	    return -1; // l'user A est mieux classé que l'user B
	}
	// l'user B a un meilleur score que l'user A pour ce quizz
	else {
	    return 1; // l'user B est mieux classé que l'user A
	}
    }

}
