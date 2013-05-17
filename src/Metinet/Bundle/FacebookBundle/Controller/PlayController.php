<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response; // réponse JSON pour l'AJAX
use Metinet\Bundle\FacebookBundle\Entity\QuizzResult;

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
     * @Route("/play/quizz/{quizzId}/question/{questionNumber}", name="play_question")
     * @Template()
     */
    public function questionAction($quizzId, $questionNumber){
	// instanciation des repositories
	$quizzRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz');
	// on récupère le quizz correspondant à l'id reçu
	$quizz = $quizzRepository->find($quizzId);
	// on récupère la question du quizz
	$question = $this->getQuizzQuestionFromQuestionNumber($questionNumber);
	// on stocke la liste des réponses à la question pour les donner à la vue
	$arrayAnswers = array();
	foreach($question->getAnswers() as $answer){
	    $arrayAnswers[] = $answer;
	}
	// on regarde s'il y a une question après celle-là pour savoir si on est à la fin du quizz ou non
	if(NULL !== $this->getQuizzQuestionFromQuestionNumber($questionNumber + 1)){
	    $nextQuestion = $questionNumber + 1;
	}
	else {
	    // on est à la fin du quizz, il n'y a plus de question suivante.
	    // on retourne -2 car il faut retourner un INT pour la vue et que -1 + 1 = 0 or 0 est un numéro de question valable.
	    $nextQuestion = -2;
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
     * Fonction appelée en AJAX qui va enregistrer la réponse de l'user pour la question du quizz à laquelle il vient de répondre
     * @Route("/play/enregistrer/userAnswer", name="play_enregistrerUserAnswer")
     * @Template()
     */
    public function enregistrerUserAnswerAction(){
	// si la fonction a été appelée par AJAX
	if($this->getRequest()->isXmlHttpRequest()){
	    // instanciation des repositories
	    $userRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:User');
	    $answerRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Answer');
	    // récupération de l'user à partir de sa connection sbook
	    $user = $this->getUserFromFacebookConnection();
	    // récupération des ids des answers (on récupère les radio button cochés du formulaire de réponse à la question)
	    $arrayIdAnswer = $this->getRequest()->get('answer');
	    // pour chaque id answer récupéré, on créé un objet Answer
	    foreach($arrayIdAnswer as $idAnswer){
		$answer = $answerRepository->find($idAnswer);
		// on ajoute l'objet Answer à l'User
		$user->addAnswer($answer);
	    }
	    // on merge l'User en BDD pour enregistrer ses réponses au quizz
	    $em = $this->getDoctrine()->getEntityManager();
	    $em->persist($user);
            $em->flush();
	    // on retourne un json disant que l'enregistrement a été fait
	    return new Response(json_encode(array("reponse" => "ok")));
	}
	// si la fonction n'a pas été appelée par AJAX, on retourne un array vide
	return array();
    }
    
    
    /**
     * Fonction appelée en AJAX qui va enregistrer la date de début du quizz
     * @Route("/play/enregistrer/onQuizzStart/{quizzId}", name="play_enregistrerOnQuizzStart")
     * @Template()
     */
    public function onQuizzStartAction($quizzId){
	// si la fonction a été appelée par AJAX
	if($this->getRequest()->isXmlHttpRequest()){
	    // instanciation des repositories
	    $quizzRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz');
	    // récupération des objets
	    $quizz = $quizzRepository->find($quizzId);
	    $user = $this->getUserFromFacebookConnection();
	    // création d'un nouvel objet QuizzResult et assignation de ses attributs
	    $quizzResult = new QuizzResult();
	    $quizzResult->setDateStart(new \DateTime("now"));
	    $quizzResult->setQuizz($quizz);
	    $quizzResult->setUser($user);
	    // enregistrement de l'objet QuizzResult en base
	    $em = $this->getDoctrine()->getEntityManager();
	    $em->persist($quizzResult);
	    $em->flush();
	    // on met en SESSION le quizzResult pour pouvoir le réutiliser plus tard
	    $session = $this->getRequest()->getSession();
	    $session->set("quizzResult", $quizzResult);
	    // on retourne un json disant que l'enregistrement a été fait
	    return new Response(json_encode(array("reponse" => "ok")));
	}
	// si la fonction n'a pas été appelée par AJAX, on retourne un array vide
	return array();
    }
    
    
    /**
     * Fonction appelée en AJAX qui va enregistrer la fin du quizz
     * @Route("/play/enregistrer/onQuizzEnd/{quizzId}", name="play_enregistrerOnQuizzEnd")
     * @Template()
     */
    public function onQuizzEndAction($quizzId){
	// si la fonction a été appelée par AJAX
	if($this->getRequest()->isXmlHttpRequest()){
	    // instanciation des repositories
	    $quizzRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz');
	    $quizz = $quizzRepository->find($quizzId);
	    // on récupère le QuizzResult en SESSION
	    $session = $this->getRequest()->getSession();
	    $quizzResult = $session->get("quizzResult");
	    $quizzResult->setDateEnd(new \DateTime("now"));
	    
	    // on retourne un json disant que l'enregistrement a été fait
	    return new Response(json_encode(array("reponse" => "ok")));
	}
	// si la fonction n'a pas été appelée par AJAX, on retourne un array vide
	return array();
    }
    
    
    /**
     * Fonction qui retourne la question N° $questionNumber si elle est présente dans le tableau de correspondance en session
     * entre l'ordre des Questions du quizz et leur id (pour les questions du quizz dans l'ordre aléatoire).
     * @param INT $questionNumber   Le numéro de la Question du quizz.
     * @return QUESTION	Un objet Question si la question existe, NULL sinon.
     */
    private function getQuizzQuestionFromQuestionNumber($questionNumber){
	if(isset($questionNumber) && is_numeric($questionNumber)){
	    // instanciation des repositories
	    $questionRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Question');
	    // récupération de l'array de correspondance en session
	    $session = $this->getRequest()->getSession();
	    $arrayCorrespondanceOrdreQuestions = $session->get("arrayCorrespondanceOrdreQuestions");
	    // si la question n° questionNumber existe dans le tableau de correspondance
	    if(isset($arrayCorrespondanceOrdreQuestions[$questionNumber])){
		// on récupère l'objetQuestion correspondant à la question n° questionNumber du quizz
		$question = $questionRepository->find($arrayCorrespondanceOrdreQuestions[$questionNumber]);
		return $question;
	    }
	}
	return NULL;
    }
    
    
    /**
     * Fonction qui retourne un onjet User correspondant à l'utilisateur qui joue au quizz avec sa connection Facebook
     * @return USER Un objet User
     */
    private function getUserFromFacebookConnection(){
	// instanciation des repositories
	$userRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:User');
	// récupération de l'user à partir de sa connection sbook
	$userFbId = $this->container->get('metinet.manager.fbuser')->getUser();
	$userResult = $userRepository->findBy(array("fbUid" => $userFbId));
	$user = $userResult[0];
	return $user;
    }
    
    
    /**
     * Fonction qui retourne le pourcentage de bonnes réponses au quizz obtenu par l'user.
     * @param QUIZZ $quizz  Le quizz dont on doir calculer le nombre de bonnes réponses
     * @param USER $user    L'user qui a répondu au quizz
     * @return DOUBLE	    Le pourcentage de bonnes réponses au quizz fait par l'user
     */
    private function getPourcentageBonnesReponses($quizz, $user){
	// instanciation des repositories
	$answerRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Answer');
	// initialisation du pourcentage
	$pourcentage = 0;
	$nbQuestions = count($quizz->getQuestions);
	foreach($quizz->getQuestions() as $question){
	    $answer = $answerRepository->getUserAnswer($question, $user);
	    // à continuer ....
	}
    }
    
}
