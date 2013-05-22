<?php

namespace Metinet\Bundle\FacebookBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * QuizzResultRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuizzResultRepository extends EntityRepository
{
    
    /**
     * Fonction qui retourne le nombre de quizz en cours de jeu
     * @return INT Le nombre de quizz en cours de jeu
     */
    public function getNombreQuizzEnCours(){
	return $this->_em->createQuery("SELECT COUNT(quizzResult.id)
					FROM MetinetFacebookBundle:QuizzResult quizzResult
					WHERE quizzResult.dateEnd IS NULL")
		->getSingleScalarResult();
    }
    
    /**
     * Fonction qui retourne le pourcentage de réussite d'un quizz
     * @return DOUBLE Le pourcentage
     */
    public function getReussiteQuizz($quizzID){
        
        $query = $this->_em->createQueryBuilder();
        $quizz = $this->_em->getRepository('MetinetFacebookBundle:Quizz')->find($quizzID);
        $query->select('quizzResult')
        ->from('MetinetFacebookBundle:QuizzResult', 'quizzResult')
        ->where('quizzResult.quizz IN (:quizz)')
        ->setParameters(array('quizz' => $quizz));
        $result = $query->getQuery()->getResult();
        $addition = 0; 
        $nb = 0;
	    foreach($result as $row){
		$average = $row->getAverage();
                //Addition des pourcentages de réussite
                $addition += (float) $average;
                //On compte le nombre de fois que le quizz a été réalisé
                $nb++;
            }
            //Calcul du total arrondi à 2 décimal
            if($nb != 0){
                $total = round(($addition / $nb)*100, 2);
            }else{
                $total = 0;
            }
        return array(
            'total' => $total,
            'nombre' => $nb
        );
    }
    
    /**
     * Fonction qui regarde si l'user a déjà joué au quizz ou non 
     * @param QUIZZ $quizz Le quizz que l'on veut jouer
     * @param USER $user    L'user qui veut jouer au quizz
     * @return BOOL TRUE or FALSE
     */
    public function hasPlayedThisQuizz($quizz, $user){
	$paramArray = array("quizz" => $quizz,
			    "user"  => $user);
	$result = $this->_em->createQuery("SELECT COUNT(quizzResult.id)
					FROM MetinetFacebookBundle:QuizzResult quizzResult
					WHERE quizzResult.quizz = :quizz
					AND quizzResult.user = :user")
		->setParameters($paramArray)
		->getSingleScalarResult();
	if($result > 0){
	    return TRUE;
	}
	return FALSE;
    }
    
    /**
     * Fonction qui retourne le nombre de points gagnés sur ce quizz par cet user.
     * @param QUIZZ $quizz.
     * @param USER $user.
     * @return INT Le nombre de points gagnés sur ce quizz par cet user.
     */
    public function getWinPoints($quizz, $user){
	$paramArray = array(	"quizz"	=> $quizz,
				"user"	=> $user);
	return $this->_em->createQuery("SELECT quizzResult.winPoints
					FROM MetinetFacebookBundle:QuizzResult quizzResult
					WHERE quizzResult.quizz = :quizz
					AND quizzResult.user = :user")
		->setParameters($paramArray)
		->getSingleScalarResult();
    }
    
    /**
     * Fonction qui retourne l'objet QuizzResult pour l'objet quizz et l'objet user donné.
     * @param Quizz $quizz  L'objet quizz dont on cherche le QuizzResult avec L'user donné.
     * @param User $user    L'objet user dont on cherche le QuizzResult avec le quizz donné.
     * @return QuizzResult  L'objet QuizzResult que l'on cherche, NULL si l'objet n'a pas été trouvé.
     */
    public function getQuizzResultFromQuizzAndUser($quizz, $user){
	$quizzResultResult = $this->findBy(array("quizz" => $quizz, "user" => $user));
	if(isset($quizzResultResult[0])){
	    return $quizzResultResult[0];
	}
	return NULL;
    }
    
    
    /**
     * Fonction qui va classer les amis pour le quizz reçu selon leur score et leur temps de réponse.
     * @param QUIZZ $quizz  Le quizz dont on veut savoir le classement
     * @param ARRAY $arrayFriendsId Array d'id des objets User correspondant aux amis que l'on veut classer
     * @return ARRAY Array d'objets User contenant les amis classés
     */
    public function getFriendsRank($quizz, $arrayFriendsId){
	$paramArray = array(	"quizz"	    => $quizz,
				"friendsId" => $arrayFriendsId);
	$result = $this->_em->createQuery(  "SELECT quizzResult, (quizzResult.dateEnd - quizzResult.dateStart) AS dateDiff
					    FROM MetinetFacebookBundle:QuizzResult quizzResult
					    JOIN quizzResult.user user
					    WHERE quizzResult.quizz = :quizz
					    AND user.id IN (:friendsId)
					    ORDER BY quizzResult.winPoints DESC,
					    dateDiff ASC")
		->setParameters($paramArray)
		->getResult();
	$arrayFinal = array();
	foreach($result as $row){
	    if(isset($row[0])){
		// on récupère l'objet User correspondant à l'ami à partir de l'objet QuizzResult provenant de la requête
		$arrayFinal[] = $row[0]->getUser();
	    }
	}
	return $arrayFinal;
    }
    
    
    /**
     * Fonction qui retourne les n meilleurs joueurs pour le quizz choisi
     * (requete par rapport au score obtenu sur ce quizz puis par rapport au temps mis pour répondre au quizz).
     * @param QUIZZ $quizz Le quizz dont on souhaite avoir les meilleurs joueurs.
     * @param INT $nbUsers Le nombre d'objets User à retourner.
     * @return ARRAY Un tableau d'objets User.
     */
    public function getBestUsersQuizz($quizz, $nbUsers){
	$paramArray = array("quizz" => $quizz);
	$result = $this->_em->createQuery(  "SELECT quizzResult, (quizzResult.dateEnd - quizzResult.dateStart) AS dateDiff
					    FROM MetinetFacebookBundle:QuizzResult quizzResult
					    WHERE quizzResult.quizz = :quizz
					    ORDER BY quizzResult.winPoints DESC,
					    dateDiff ASC")
		->setParameters($paramArray)
		->setMaxResults($nbUsers)
		->getResult();
	$arrayFinal = array();
	foreach($result as $row){
	    if(isset($row[0])){
		// on récupère l'objet User à partir de l'objet QuizzResult provenant de la requête
		$arrayFinal[] = $row[0]->getUser();
	    }
	}
	return $arrayFinal;
    }
    
}
