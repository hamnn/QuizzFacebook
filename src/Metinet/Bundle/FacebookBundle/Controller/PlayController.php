<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PlayController extends Controller
{
    
    /**
     * Va chercher le quizz correspondant à l'id reçu et l'affiche pour commencer une partie
     * @Route("/play/{quizzId}", name="play_index")
     * @Template()
     */
    public function indexAction($quizzId)
    {
	// instanciation des repositories
	$quizzRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz');
	// on récupère un quizz au hasard
	$quizz = $quizzRepository->find($quizzId);
	// on récupère la première question du quizz
	$firstQuestion = $quizz->getQuestion(0);
	return array(	"quizz"		    => $quizz,
			"firstQuestion"	    => $firstQuestion,
			"currentQuestion"   => 0);
    }
    
    
    public function questionAction(){
	
    }
    
}
