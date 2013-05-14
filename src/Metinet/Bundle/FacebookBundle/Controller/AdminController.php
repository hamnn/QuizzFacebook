<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

class AdminController extends Controller
{
    
    
    /**
     * @Route("/admin", name="admin_index")
     * @Template()
     */
    public function indexAction()
    {
	// instanciation des repositories
	$userRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:User');
	$quizzRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz');
	$quizzResultRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:QuizzResult');
	// on récupère les joueurs créés ces 7 derniers jours
	$arrayJoueurs7Jours = $userRepository->getJoueursSurNDerniersJours(7);
	// on récupère les joueurs créés ces 30 derniers jours
	$arrayJoueurs30Jours = $userRepository->getJoueursSurNDerniersJours(30);
	// on récupère le nombre de quizz disponibles
	$nbQuizzDisponibles = $quizzRepository->getNombreQuizzDisponibles();
	// on récupère le score moyen de tous les joueurs
	$scoreMoyenJoueurs = $userRepository->getScoreMoyenDeTousLesJoueurs();
	// on récupère le nombre total de joueurs
	$nbTotalJoueurs = $userRepository->getNombreTotalJoueurs();
	// on récupère le nombre de quizz en cours de jeu
	$nbQuizzEnCours = $quizzResultRepository->getNombreQuizzEnCours();
	// on récupère le top 3 des quizz les plus joués
	$top3QuizzPlusPopulaires = $quizzRepository->getTopQuizzPopulaires(3, "DESC");
	// on récupère le top 3 des quizz les moins joués
	$top3QuizzMoinsPopulaires = $quizzRepository->getTopQuizzPopulaires(3, "ASC");
	// on récupère les 10 derniers utilisateurs
	$array10DerniersUsers = $userRepository->getDerniersUtilisateurs(10);
	return array(	"arrayJoueurs7Jours"	    => $arrayJoueurs7Jours,
			"arrayJoueurs30Jours"	    => $arrayJoueurs30Jours,
			"nbQuizzDisponibles"	    => $nbQuizzDisponibles,
			"scoreMoyenJoueurs"	    => $scoreMoyenJoueurs,
			"nbTotalJoueurs"	    => $nbTotalJoueurs,
			"nbQuizzEnCours"	    => $nbQuizzEnCours,
			"top3QuizzPlusPopulaires"   => $top3QuizzPlusPopulaires,
			"top3QuizzMoinsPopulaires"  => $top3QuizzMoinsPopulaires,
			"array10DerniersUsers"	    => $array10DerniersUsers);
    }
    
    /**
     * @Route("/admin/login", name="login")
     * @Template()
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('MetinetFacebookBundle:Admin:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
    
    
    /**
     * @Route("/admin/login_check", name="login_check")
     * @Template()
     */
    public function loginCheckAction()
    {
	// Symfony s'occupe du login_check
    }
    
    
    /**
     * @Route("/admin/logout", name="logout")
     * @Template()
     */
    public function logoutAction()
    {
	// Symfony s'occupe de me délogger
    }
}
