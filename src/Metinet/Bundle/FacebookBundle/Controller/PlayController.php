<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends Controller
{
    
    /**
     * @Route("/play", name="play_index")
     * @Template()
     */
    public function indexAction()
    {
	// instanciation des repositories
	$quizzRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz');
	// on récupère un quizz au hasard
	$quizz = $quizzRepository->getRandomQuizz();
	// on mélange les questions du quizz
	shuffle($quizz->getQuestions());
	// on récupère la première question du quizz
	$firstQuestion = $quizz->getFirstQuestion();
	return array(	"quizz"		=> $quizz,
			"firstQuestion"	=> $firstQuestion);
    }
    
}
