<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Metinet\Bundle\FacebookBundle\Entity\Quizz;
use Metinet\Bundle\FacebookBundle\Entity\Question;
use Metinet\Bundle\FacebookBundle\Form\Type\QuizzType;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;

/**
 * Quizz controller.
 *
 */
class QuizzController extends MetinetController {

    /**
     * Lists all Quizz entities.
     *
     * @Route("/admin/quizz", name="quizz")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MetinetFacebookBundle:Quizz')->findAll();
        $quizzResultRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:QuizzResult');
        
        foreach($entities as $entity){
            
            $tableau = $quizzResultRepository->getReussiteQuizz($entity->getId());
            
            $entity->setPourcentage($tableau['total']);
            $entity->setNbParticipation($tableau['nombre']);
        }
        
        return array(
            'entities' => $entities,
        );
    }


    /**
     * Finds and displays a Quizz entity.
     *
     * @Route("/admin/{id}/showquizz", name="quizz_show")
     * @Template()
     */
    public function showAction($id) {
        // instanciation des repositories
	//$questionRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz');
	// on récupère les questions du quizz
	//$arrayQuestions = $questionRepository->getQuestionsByQuizzID($id);

        $entity = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quizz entity.');
        }
        
 
        
        $deleteForm = $this->createDeleteForm($id);
        return array(
            'questions' => $entity->getQuestions(),
            'entity' => $entity,
            'theme' => $entity->getTheme(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Quizz entity.
     *
     * @Route("/admin/newquizz", name="quizz_new")
     * @Template()
     */
    public function newAction() {
        $entity = new Quizz();
        $form = $this->createForm(new QuizzType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Quizz entity.
     *
     * @Route("/admin/createquizz", name="quizz_create")
     * @Template("MetinetFacebookBundle:Quizz:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new Quizz();
        $form = $this->createForm(new QuizzType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //$entity->upload();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('quizz_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Quizz entity.
     *
     * @Route("/admin/{id}/editquizz", name="quizz_edit")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Quizz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quizz entity.');
        }

        $editForm = $this->createForm(new QuizzType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Quizz entity.
     *
     * @Route("/admin/{id}/updatequizz", name="quizz_update")
     * @Template("MetinetFacebookBundle:Quizz:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Quizz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quizz entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new QuizzType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('quizz_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    /**
     * Edits state Quizz entity.
     *
     * @Route("/admin/{id}/statequizz", name="quizz_state")
     * @Template("MetinetFacebookBundle:Quizz:index.html.twig")
     */
    public function stateAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Quizz')->find($id);
        $isstate = $entity->getState();
        if ($isstate == 1){
            $entity->setState(0);
        }else{
            $entity->setState(1);
        }
        
        $em->persist($entity);
        $em->flush();
            

        $entities = $em->getRepository('MetinetFacebookBundle:Quizz')->findAll();
        $quizzResultRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:QuizzResult');
        
        foreach($entities as $entity){
            
            $tableau = $quizzResultRepository->getReussiteQuizz($entity->getId());
            
            $entity->setPourcentage($tableau['total']);
            $entity->setNbParticipation($tableau['nombre']);
        }
        
        return array(
            'entities' => $entities,
            
        );
    }
    
    /**
     * Edits isPromoted Quizz entity.
     *
     * @Route("/admin/{id}/ispromotedquizz", name="quizz_ispromoted")
     * @Template("MetinetFacebookBundle:Quizz:index.html.twig")
     */
    public function isPromotedAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Quizz')->find($id);
        $ispromoted = $entity->getIsPromoted();
        if ($ispromoted == 1){
            $entity->setIsPromoted(0);
        }else{
            $entity->setIsPromoted(1);
        }
        
        $em->persist($entity);
        $em->flush();
            
        $entities = $em->getRepository('MetinetFacebookBundle:Quizz')->findAll();
        $quizzResultRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:QuizzResult');
        
        foreach($entities as $entity){
            
            $tableau = $quizzResultRepository->getReussiteQuizz($entity->getId());
            
            $entity->setPourcentage($tableau['total']);
            $entity->setNbParticipation($tableau['nombre']);
        }
        
        return array(
            'entities' => $entities,
            
        );
    }

    /**
     * Deletes a Quizz entity.
     *
     * @Route("/admin/{id}/deletequizz", name="quizz_delete")
     * 
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        //Erreur suite a la suppression avec l'ajax .. if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MetinetFacebookBundle:Quizz')->find($id);
            
            
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Quizz entity.');
            }
                // on enregistre les modifs en BDD
                $em->remove($entity);
                $em->flush();
            
       // }

        return $this->redirect($this->generateUrl('quizz'));
    }

    /**
     * Deletes a Quizz entity.
     *
     * @Route("/admin/deleteallquizz", name="quizz_deleteall")
     * @Template()
     */
    public function deleteAllAction() {
        $em = $this->getDoctrine()->getManager();
        $allQuizz = $em->getRepository('MetinetFacebookBundle:Quizz')->findAll();
        $currentQuizz = $this->getQuizz();
        foreach ($allQuizz as $oneQuizz){
            if($oneQuizz->getId() != $currentQuizz->getId()) $em->remove($oneQuizz);
        }
            
        $em->flush();

        return $this->redirect($this->generateUrl('homepage'));
    }    

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }
    
    
    
    /**
     * Fonction qui affiche la page de détail d'un quizz avec possibilité de jouer à ce quizz
     * @Route("/front/quizz/details/{quizzId}", name="quizz_frontDetails")
     * @Template()
     */
    public function detailsAction($quizzId){
	// instanciation des repositories
        $quizzRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz');
	$userRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:User');
	$quizzResultRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:QuizzResult');
	$questionRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Question');
	$quizz = $quizzRepository->find($quizzId);
	// récupération du classement des amis
	$arrayFriendsRank = $this->getFriendsRank($quizz);
	// récupération du classement des 10 meilleurs joueurs du quizz
	$arrayBestUsers = $this->getBestUsersQuizz($quizz, 10);
	// récupération de l'user
	$user = $this->getUserFromFacebookConnection();
	// on precise si l'user a déjà joué à ce quizz
	$hasPlayedThisQuizz = $quizzResultRepository->hasPlayedThisQuizz($quizz, $user);
	// si l'user a joué à ce quizz, on lui transmet son score et son temps de réponse
	$txtWin = "";
	$dateEnd = "";
	if($hasPlayedThisQuizz){
	    $quizzResult = $quizzResultRepository->getQuizzResultFromQuizzAndUser($quizz, $user);
	    $user->setWinPointsForThisQuizz($quizzResult->getWinPoints());
	    $user->setTimeForThisQuizz($quizzResult->getTimeIntervalEnSecondes());
	    $txtWin = $quizz->getTxtWin($quizzResult->getAverage());
	    $dateEnd = $quizzResult->getDateEnd()->format('Y-m-d H:i:s');
	}
	// on récupère le fb_app_id depuis parameters.yml
	$fbAppId = $this->container->getParameter('fb_app_id');
	// on récupère le nombre de questions pour le quizz
	$nbQuestions = $questionRepository->getNombreQuestionsPourQuizz($quizz);
	return array(	"quizz"			=> $quizz,
			"user"			=> $user,
			"txtWin"		=> $txtWin,
			"dateEnd"		=> $dateEnd,
			"arrayFriends"		=> $arrayFriendsRank["arrayFriends"],
			"arrayFriendsADefier"	=> $arrayFriendsRank["arrayFriendsADefier"],
			"arrayBestUsers"	=> $arrayBestUsers,
			"hasPlayedThisQuizz"	=> $hasPlayedThisQuizz,
			"fbAppId"		=> $fbAppId,
			"nbQuestions"		=> $nbQuestions);
    }

    
    /**
     * Fonction qui retourne un array 2D qui contient 2 arrays : un array d'objets User qui sont les amis de l'user qui ont joué au quizz,
     * un array d'amis à défier qui n'ont pas déjà joués à ce quizz.
     * Les amis qui ont joués sont classés par score décroissant.
     * @param type $quizz
     * @return ARRAY contenant	- un array d'objets User qui sont les amis de l'user qui ont joués à ce quizz
     *				- un array d'array contenent les amis Facebook qui n'ont pas joué au quizz, pour les défier
     */
    private function getFriendsRank($quizz){
	// instanciation des repositories
	$userRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:User');
	$quizzResultRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:QuizzResult');
	// récupération de l'user et de ses amis
	$user = $this->getUserFromFacebookConnection();
	$userFriends = $this->container->get('metinet.manager.fbuser')->getUserFriends($user->getFbUid());
	// tableau d'amis classés pour ce quizz
	$arrayFinal = array();
	$arrayFriends = array(); // tableau des amis (objets User) qui ont joué au quizz classé par score
	$arrayFriendsADefier = array(); // tableau des amis (pas d'objets User) qui n'ont pas joué au quizz
	$arrayFriendsId = array(); // tableau contenant les id des objets User correspondant aux amis
	// on parcours les amis de l'user
	foreach($userFriends['data'] as $userFriend){
	    // $userFriend = array(2) { 'name' => string(12) "Prénom Nom" 'id' => string(10) "1250139621" } id est le fbId
	    // on créé un objet User pour chaque ami de l'user
	    $friend = $userRepository->getUserFromFbId($userFriend['id']);
	    // si l'ami existe dans la base
	    if(NULL !== $friend){
		// si l'ami a déjà joué au quizz
		if($quizzResultRepository->hasPlayedThisQuizz($quizz, $friend)){
		    // on l'ajoute son id au tableau d'id des amis pour effectuer la requete du classement
		    $arrayFriendsId[] = $friend->getId();
		}
		// l'ami n'a pas joué au quizz, on le garde pour lui afficher un bouton défier qui publiera un message sur son mur
		else {
		    $arrayFriendsADefier[] = $userFriend;
		}
	    }
	    // l'ami n'existe pas dans la base, on le garde pour lui afficher un bouton défier qui publiera un message sur son mur
	    else {
		$arrayFriendsADefier[] = $userFriend;
	    }
	}
	// on récupère le classement des amis pour ce quizz
	$arrayFriends = $quizzResultRepository->getFriendsRank($quizz, $arrayFriendsId);
	foreach($arrayFriends as $friend){
	    $quizzResultFriend = $quizzResultRepository->getQuizzResultFromQuizzAndUser($quizz, $friend);
	    $friend->setWinPointsForThisQuizz($quizzResultFriend->getWinPoints());
	    $friend->setTimeForThisQuizz($quizzResultFriend->getTimeIntervalEnSecondes());
	}
	// ajout des array de Friends, des scores et des amis à défier à l'array final à retourner
	$arrayFinal["arrayFriends"] = $arrayFriends;
	$arrayFinal["arrayFriendsADefier"] = $arrayFriendsADefier;
	return $arrayFinal;
    }

    
    /**
     * Fonction qui retourne un array d'Users qui sont les n meilleurs joueurs du quizz choisi.
     * @param QUIZZ $quizz Le quizz dont on souhaite avoir les meilleurs joueurs.
     * @param INT $nbUsers Le nombre d'User à retourner.
     * @return ARRAY Un array d'objets User.
     */
    private function getBestUsersQuizz($quizz, $nbUsers){
	// instanciation des repositories
	$quizzResultRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:QuizzResult');
	// récupération des meilleurs joueurs pour ce quizz
	$arrayUsers = $quizzResultRepository->getBestUsersQuizz($quizz, $nbUsers);
	// pour chaque user, on va lui attribuer son score et son temps de jeu pour ce quizz pour pouvoir les afficher
	foreach($arrayUsers as $user){
	    $quizzResult = $quizzResultRepository->getQuizzResultFromQuizzAndUser($quizz, $user);
	    $user->setWinPointsForThisQuizz($quizzResult->getWinPoints());
	    $user->setTimeForThisQuizz($quizzResult->getTimeIntervalEnSecondes());
	}
	// on retourne le tableau des meilleurs joueurs pour ce quizz
	return $arrayUsers;
    }
}
