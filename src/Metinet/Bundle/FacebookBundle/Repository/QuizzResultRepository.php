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
     * Fonction qui retourne le top n des quizz les plus ou moins populaires
     * @param INT $limit Le nombre de quizz à retourner
     * @param STRING $order L'ordre des quizz (les plus populaires ou les moins populaires). $ordre doit être "ASC" ou "DESC"
     * @return ARRAY d'objets QuizzResult
     */
    public function getTopQuizzPopulaires($limit, $ordre){
	if(isset($limit) && $limit > 0 && isset($ordre) && ($ordre == "ASC" || $ordre == "DESC")){
	    
	    $qb = $this->_em->createQueryBuilder();
	    $qb->select('qr')
		    ->addSelect('COUNT(q.id) as nbTimesPlayed')
		    ->from('MetinetFacebookBundle:QuizzResult', 'qr')
		    ->leftJoin('qr.quizz', 'q')
		    ->groupBy('q.id')
		    ->orderBy('nbTimesPlayed', $ordre)
		    ->setMaxResults($limit);
	    return $qb->getQuery()->getResult();
	    
	    /*
	    // array d'objets quizz à retourner
	    $arrayFinal = array();
	    // on récupère les quizzResult qui correspondent aux quizz du top
	    $query = $this->_em->createQuery("	SELECT quizzResult, COUNT(quizzResult) as nbTimesPlayed
						FROM MetinetFacebookBundle:QuizzResult quizzResult
						GROUP BY quizzResult.quizz
						ORDER BY nbTimesPlayed ".$ordre."")
		    ->setMaxResults($limit);
	    $result = $query->getResult();
	    // pour chaque quizzResult, on récupère le quizz correspondant
	    foreach($result as $quizzResult){
		$paramArray = array("quizz_id" => $quizzResult->getQuizz()->getId());
		$queryQuizz = $this->_em->createQuery("	SELECT quizz
							FROM MetinetFacebokkBundle:Quizz quizz
							WHERE quizz.id LIKE :quizz_id")
			->setParameters($paramArray);
		// on ajoute le quizz au tableau de quizz à retourner
		$arrayFinal[] = $queryQuizz->getResult();
	    }
	    var_dump($arrayFinal);die();
	    return $arrayFinal;
	}
*/
	}
    }
}
