<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response; // réponse JSON pour l'AJAX

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
	// on récupère le quizz correspondant à l'id reçu
	$quizz = $quizzRepository->find($quizzId);
	// on créé un tableau de correspondance entre les ids des Questions et leur numéro de question pour pouvoir mélanger l'ordre des questions
	// la key de l'array est le numéro de question, la value de l'array est l'id de la Question
	$arrayCorrespondanceOrdreQuestions = array();
	foreach($quizz->getQuestions() as $question){
	    $arrayCorrespondanceOrdreQuestions[] = $question->getId();
	}
	// on mélange l'array de correspondance pour avoir un ordre de déroulement des questions aléatoire
	shuffle($arrayCorrespondanceOrdreQuestions);
	// on enregistre l'array de correspondance dans une variable de session
	// pour pouvoir la réutiliser plus tard lors du chargement des prochaines questions
	$session = $this->getRequest()->getSession();
	$session->set("arrayCorrespondanceOrdreQuestions", $arrayCorrespondanceOrdreQuestions);
	return array(	"quizz"		=> $quizz,
			"nextQuestion"	=> 0);
    }
    
    
    /**
     * Va chercher la question N° questionNumber du quizz correspondant à l'id reçu
     * et l'affiche pour que le joueur puisse répondre.
     * CETTE FONCTION EST APPELLÉE EN AJAX
     * @Route("/play/{quizzId}/{questionNumber}", name="play_question")
     * @Template()
     */
    public function questionAction($quizzId, $questionNumber){
	// instanciation des repositories
	$quizzRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz');
	// on récupère le quizz correspondant à l'id reçu
	$quizz = $quizzRepository->find($quizzId);
	// on récupère la question du quizz
	$question = $quizz->getQuestion($questionNumber);
	// on stocke la liste des réponses à la question pour les donner à la vue
	$arrayAnswers = array();
	foreach($question->getAnswers() as $answer){
	    $arrayAnswers[] = $answer;
	}
	// on regarde s'il y a une question après celle-là pour savoir si on est à la fin du quizz ou non
	if(NULL !== $quizz->getQuestion($questionNumber + 1)){
	    $nextQuestion = $questionNumber + 1;
	}
	else {
	    $nextQuestion = -2; // on est à la fin du quizz, il n'y a plus de question suivante
	}

	// on génère la vue de la question à afficher
	$render = $this->renderView("MetinetFacebookBundle:Play:question.html.twig",
			    array(  "quizz"	    => $quizz,
				    "question"	    => $question,
				    "nextQuestion"  => $nextQuestion,
				    "answers"	    => $arrayAnswers));
	// on retourne l'objet reponse AJAX contenant le json de la vue de la question à afficher
	return new Response(json_encode(array("question" => $render)));
    }
    
    
    /**
     * Fonction appelée en AJAX qui va enregistrer la réponse de l'user
     * @Route("/play/user/{userFbId}/answer/{answerId}", name="play_enregistrerUserAnswer")
     * @Template()
     */
    public function enregistrerUserAnswerAction(){
	
    }
    
}
