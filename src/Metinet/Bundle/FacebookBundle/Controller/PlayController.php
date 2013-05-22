<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response; // réponse JSON pour l'AJAX
use Metinet\Bundle\FacebookBundle\Entity\QuizzResult;
use Symfony\Component\HttpFoundation\Session\Session;

class PlayController extends Controller {

    //Variable pour savoir si nous sommes en présence d'un high score ou non 
    var $highScore = true;

    /**
     * Va chercher le quizz correspondant à l'id reçu et l'affiche pour commencer une partie
     * @Route("/play{quizzId}", name="play_index")
     * @Template()
     */
    public function indexAction($quizzId) {
        // instanciation des repositories
        $quizzRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz');
        // on récupère le quizz correspondant à l'id reçu
        $quizz = $quizzRepository->find($quizzId);
        // on créé un tableau de correspondance entre les ids des Questions et leur numéro de question pour pouvoir mélanger l'ordre des questions
        // la key de l'array est le numéro de question, la value de l'array est l'id de la Question
        $arrayCorrespondanceOrdreQuestions = array();
        foreach ($quizz->getQuestions() as $question) {
            $arrayCorrespondanceOrdreQuestions[] = $question->getId();
        }
        // on mélange l'array de correspondance pour avoir un ordre de déroulement des questions aléatoire
        shuffle($arrayCorrespondanceOrdreQuestions);
        // on enregistre l'array de correspondance dans une variable de session
        // pour pouvoir la réutiliser plus tard lors du chargement des prochaines questions
        $session = $this->getRequest()->getSession();
        $session->set("arrayCorrespondanceOrdreQuestions", $arrayCorrespondanceOrdreQuestions);
        return array("quizz" => $quizz,
            "nextQuestion" => 0);
    }

    /**
     * Va chercher la question N° questionNumber du quizz correspondant à l'id reçu
     * et l'affiche pour que le joueur puisse répondre.
     * CETTE FONCTION EST APPELLÉE EN AJAX
     * @Route("/play/quizz/{quizzId}/question/{questionNumber}", name="play_question")
     * @Template()
     */
    public function questionAction($quizzId, $questionNumber) {
        // instanciation des repositories
        $quizzRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz');
        // on récupère le quizz correspondant à l'id reçu
        $quizz = $quizzRepository->find($quizzId);
        // on récupère la question du quizz
        $question = $this->getQuizzQuestionFromQuestionNumber($questionNumber);
        // on stocke la liste des réponses à la question pour les donner à la vue
        $arrayAnswers = array();
        foreach ($question->getAnswers() as $answer) {
            $arrayAnswers[] = $answer;
        }
        // on regarde s'il y a une question après celle-là pour savoir si on est à la fin du quizz ou non
        if (NULL !== $this->getQuizzQuestionFromQuestionNumber($questionNumber + 1)) {
            $nextQuestion = $questionNumber + 1;
        } else {
            // on est à la fin du quizz, il n'y a plus de question suivante.
            // on retourne -2 car il faut retourner un INT pour la vue et que -1 + 1 = 0 or 0 est un numéro de question valable.
            $nextQuestion = -2;
        }
        // on génère la vue de la question à afficher
        $render = $this->renderView("MetinetFacebookBundle:Play:question.html.twig", array("quizz" => $quizz,
            "question" => $question,
            "nextQuestion" => $nextQuestion,
            "answers" => $arrayAnswers));
        // on retourne l'objet reponse AJAX contenant le json de la vue de la question à afficher
        return new Response(json_encode(array("question" => $render)));
    }

    /**
     * Fonction appelée en AJAX qui va enregistrer la réponse de l'user pour la question du quizz à laquelle il vient de répondre
     * @Route("/play/enregistrer/userAnswer", name="play_enregistrerUserAnswer")
     * @Template()
     */
    public function enregistrerUserAnswerAction() {
        // si la fonction a été appelée par AJAX
        if ($this->getRequest()->isXmlHttpRequest()) {
            // instanciation des repositories
            $userRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:User');
            $answerRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Answer');
            // récupération de l'user à partir de sa connection sbook
            $user = $this->getUserFromFacebookConnection();
            // récupération des ids des answers (on récupère les radio button cochés du formulaire de réponse à la question)
            $arrayIdAnswer = $this->getRequest()->get('answer');
            // pour chaque id answer récupéré, on créé un objet Answer
            foreach ($arrayIdAnswer as $idAnswer) {
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
    public function onQuizzStartAction($quizzId) {
        // si la fonction a été appelée par AJAX
        if ($this->getRequest()->isXmlHttpRequest()) {
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
            // on met en SESSION l'id du quizzResult pour pouvoir le réutiliser plus tard
            $session = $this->getRequest()->getSession();
            $session->set("quizzResultId", $quizzResult->getId());
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
    public function onQuizzEndAction($quizzId) {
        // si la fonction a été appelée par AJAX
        if ($this->getRequest()->isXmlHttpRequest()) {
            // instanciation des repositories
            $quizzRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz');
            $quizzResultRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:QuizzResult');
            // récupération du quizz
            $quizz = $quizzRepository->find($quizzId);
            // on récupère l'id du QuizzResult en SESSION pour le regénérer
            $session = $this->getRequest()->getSession();
            $quizzResult = $quizzResultRepository->find($session->get("quizzResultId"));
            // on set la date de fin du quizz
            $quizzResult->setDateEnd(new \DateTime("now"));
            // on set le pourcentage de bonnes réponses
            $quizzResult->setAverage($this->getPourcentageBonnesReponses($quizz, $quizzResult->getUser()));
            // on set les points de bonnes réponses en prenant en compte les bonus / malus
            $quizzResult->setWinPoints($this->getWinPointsWithBonusOrMalus($quizz, $quizzResult));
            // on récupère le message de fin de quizz en fonction du nombre de bonnes réponses
            $txtWin = $this->getTxtWin($quizz, $quizzResult->getAverage());
            // on récupère l'user pour lui ajouter ses points et incrémenter son nombre de quizz
            $user = $quizzResult->getUser();
            $user->setPoints($user->getPoints() + $quizzResult->getWinPoints());
            $user->setNbQuizz($user->getNbQuizz() + 1);
            // enregistrement des objets en base
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($quizzResult);
            $em->persist($user);
            $em->flush();
            // on nettoie les variables de session utilisées pour jouer au quizz
            $session->remove("arrayCorrespondanceOrdreQuestions");
            $session->remove("quizzResultId");
            // on génère la vue de la fin du quizz
            $render = $this->renderView("MetinetFacebookBundle:Play:quizzEnd.html.twig", array("quizzResult" => $quizzResult,
                "txtWin" => $txtWin, 'highscore' => $this->highscore));

            //Envoi des notifications aux utilisateurs dont le résultat à été battu
            $this->friendNotificationAction($quizzId, $quizzResult->getWinPoints());

            // on retourne l'objet reponse AJAX contenant le json de la vue de la fin du quizz
            return new Response(json_encode(array("quizzEnd" => $render)));
        }

        // si la fonction n'a pas été appelée par AJAX, on retourne un array vide
        return array();
    }

    /**
     * Fonction qui retourne la question N° $questionNumber si elle est présente dans le tableau de correspondance en session
     * entre l'ordre des Question
     * s du quizz et leur id (pour les questions du quizz dans l'ordre aléatoire).
     * @param INT $questionNumber   Le numéro de la Question du quizz.
     * @return QUESTION	Un objet Question si la question existe, NULL sinon.
     */
    private function getQuizzQuestionFromQuestionNumber($questionNumber) {
        if (isset($questionNumber) && is_numeric($questionNumber)) {
            // instanciation des repositories
            $questionRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Question');
            // récupération de l'array de correspondance en session
            $session = $this->getRequest()->getSession();
            $arrayCorrespondanceOrdreQuestions = $session->get("arrayCorrespondanceOrdreQuestions");
            // si la question n° questionNumber existe dans le tableau de correspondance
            if (isset($arrayCorrespondanceOrdreQuestions[$questionNumber])) {
                // on récupère l'objetQuestion correspondant à la question n° questionNumber du quizz
                $question = $questionRepository->find($arrayCorrespondanceOrdreQuestions[$questionNumber]);
                return $question;
            }
        }
        return NULL;
    }

    /**
     * @Route("/play/notification/{quizzId}/{quizzScore}", name="play_friendNotificationAction")
     * @Template()
     */
    public function friendNotificationAction($quizzId, $quizzScore) {

        $session = $this->getRequest()->getSession();
        $user = $session->get('user');

        //On récupère tous les quizz_results en fonction du user_id et du quizz_id
        $quizzRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:QuizzResult');
        $quizzResults = $quizzRepository->findBy(array("user" => $user['id'], 'quizz' => $quizzId));


        foreach ($quizzResults as $oneQuizzresult) {
            //Si on trouve un résultat dans la base de donnée qui est supérieur au résultat en cours on sort de la boucle.
            if ($oneQuizzresult->getWinPoints() > $quizzScore) {
                $this->highScore = false;
                break;
            }
        }

        if (!$this->highScore)
            return NULL;

        //Si c'est un High Score, on charge la liste de tous les amis fb de l'utilisateur
        $userFriends = $this->container->get('metinet.manager.fbuser')->getUserFriends($user['fb_uid']);

        //Pour chaque utilisateur on extrait l'ID correspondant
        $friendsId = array();
        foreach ($userFriends['data'] as $index => $friend)
            $friendsId[] = $friend['id'];


        //On récupère tous les quizz_results en fonction du user_id et du quizz_id
        $em = $this->getDoctrine()->getEntityManager();

        $query = $em->createQueryBuilder();
        $query->select('quizzResult')
                ->addSelect('user')
                ->from('MetinetFacebookBundle:QuizzResult', 'quizzResult')
                ->leftJoin('quizzResult.user', 'user')
                ->where('user.fbUid IN (:friendsId)')
                ->andWhere('quizzResult.winPoints < (:winPoints)')
                ->setParameters(array('winPoints' => $quizzScore, 'friendsId' => $friendsId));

        $friendsToNotif = $query->getQuery()->getResult();

        //On envoi des notifications aux amis dont le score vient d'être battu
        foreach ($friendsToNotif as $oneFriend) {
            $response = $this->container->get('metinet.manager.fbuser')->api('/' . $oneFriend->getUser()->getfbUid() . '/notifications', 'POST', array(
                'template' => 'CouCou tu veux voir ma bite? ',
                'href' => 'play' . $quizzId,
                'access_token' => "575560672464968|YiKfCuPGRy5WwCgkWxO_vYkKmrg"
                    ));
        }

        //On publie le nouveau highscore sur le mur 
        try {
            $attachment = array('message' => 'Je viens de battre mon record sur YouQuizz ! Mon nouveau score est de : '.$quizzScore,
                'name' => 'Nouveau meilleur score sur YouQuizz',
                'caption' => 'Le titre de ton post sur le Mur',
                'picture' => 'http://www.startingform.sitew.com//fs/Root/normal/hf57-Point_d_interrogation.png');

            $results = $this->container->get('metinet.manager.fbuser')->api('/'.$user['fb_uid'].'/feed/', 'post', $attachment);
        } catch (FacebookApiException $e) {
            echo $e->getMessage();
            $user = null;
        }
        
    }

    /**
     * Fonction qui retourne un onjet User correspondant à l'utilisateur qui joue au quizz avec sa connection Facebook
     * @return USER Un objet User
     */
    private function getUserFromFacebookConnection() {
        // instanciation des repositories
        $userRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:User');
        // récupération de l'user à partir de sa connection sbook
        $userFbId = $this->container->get('metinet.manager.fbuser')->getUser();
        $userResult = $userRepository->findBy(array("fbUid" => $userFbId));
        $user = $userResult[0];
        return $user;
    }

    /**
     * Fonction qui retourne le pourcentage (entre 0 et 1) de bonnes réponses au quizz obtenu par l'user.
     * @param QUIZZ $quizz  Le quizz dont on doit calculer le nombre de bonnes réponses
     * @param USER $user    L'user qui a répondu au quizz
     * @return DOUBLE	    Le pourcentage de bonnes réponses au quizz fait par l'user (pourcentage entre 0 et 1)
     */
    private function getPourcentageBonnesReponses($quizz, $user) {
        // instanciation des repositories
        $answerRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Answer');
        // initialisation des variables
        $pourcentageBonnesReponses = 0;
        $nbQuestions = count($quizz->getQuestions());
        $reponsesJustes = 0;
        // on récupère toutes les réponses de l'user
        foreach ($quizz->getQuestions() as $question) {
            $answer = $answerRepository->getUserAnswer($question, $user);
            // si la réponse de l'user est correcte, on incrémente le nombre de réponses justes
            if ($answer->getIsCorrect()) {
                $reponsesJustes++;
            }
        }
        // on calcul le pourcentage de réponses justes par rapport au nombre de questions posées
        $pourcentageBonnesReponses = $reponsesJustes / $nbQuestions;
        // on retourne le pourcentage de réponses justes qui est un double compris entre 0 et 1
        return $pourcentageBonnesReponses;
    }

    /**
     * Fonction qui va calculer le nombre de points obtenus au quizz en fonction des bonnes réponses,
     * du temps de réponse et des bonus / malus qui en découlent.
     * @param QUIZZ $quizz Le quizz auquel on vient de jouer.
     * @param QUIZZRESULT Le résultat du quizz auquel on vient de jouer.
     * @return INT  Le nombre de points obtenus.
     */
    private function getWinPointsWithBonusOrMalus($quizz, $quizzResult) {
        $winPoints = 0;
        // calcul des winPoints en fonction des réponses justes
        $winPoints = $quizz->getWinPoints() * $quizzResult->getAverage();
        // détermination des bonus / malus
        $tempsReponseEnSecondes = $quizzResult->getDateEnd()->getTimestamp() - $quizzResult->getDateStart()->getTimestamp();
        // si l’utilisateur répond à toutes les questions avant le temps moyen et obtient au moins 75% de bonnes réponses
        if ($tempsReponseEnSecondes <= $quizz->getAverageTime() && $quizzResult->getAverage() >= 0.75) {
            // il gagne un bonus de points équivalent à 25% des points à gagner sur le quizz.
            $winPoints += $quizz->getWinPoints() * 0.25;
        }
        // si l’utilisateur répond aux questions après le temps imparti
        elseif ($tempsReponseEnSecondes > $quizz->getAverageTime()) {
            //  un malus vient s’appliquer équivalent à 15% des points à gagner sur le quizz.
            $winPoints -= $quizz->getWinPoints() * 0.15;
        }
        // on retourne les points gagnés (en INT) avec leurs bonus / malus
        return intval($winPoints);
    }

    /**
     * Fonction qui retourne le texte de fin de quizz en fonction du pourcentage de bonnes réponses.
     * @param QUIZZ $quizz	Le quizz auquel l'user vient de jouer.
     * @param FLOAT $average	Le pourcentage (entre 0 et 1) de bonnes réponses au quizz.
     * @return STRING	Le texte de fin de quizz en fonction du pourcentage de bonnes réponses.
     */
    private function getTxtWin($quizz, $average) {
        $txtWin = "";
        if ($average >= 0 && $average <= 0.25) {
            $txtWin = $quizz->getTxtWin1();
        } elseif ($average >= 0.26 && $average <= 0.50) {
            $txtWin = $quizz->getTxtWin2();
        } elseif ($average >= 0.51 && $average <= 0.75) {
            $txtWin = $quizz->getTxtWin3();
        } elseif ($average >= 0.76 && $average <= 1) {
            $txtWin = $quizz->getTxtWin4();
        }
        return $txtWin;
    }

}
