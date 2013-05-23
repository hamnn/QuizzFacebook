<?php

namespace Metinet\Bundle\FacebookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Metinet\Bundle\FacebookBundle\Repository\UserRepository")
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="fb_uid", type="string", length=50)
     */
    private $fbUid;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=100)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=100)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    private $picture;

    /**
     * @var float
     *
     * @ORM\Column(name="points", type="float", nullable=true)
     */
    private $points;
    
    
    /**
     * Variable qui contient le score de l'user pour le quizz en cours de consultation (en non en cours de jeu).
     * Utilisé pour lister les amis en fonction de leur score sur ce quizz.
     * N'apparait pas en BDD.
     * @var type INT
     */
    private $winPointsForThisQuizz;
    
    
    /**
     * Variable qui contient le temps de réponse en secondes au quizz en cours de consultation
     * Utilisé pour lister les amis en fonction de leur score sur ce quizz.
     * N'apparait pas en BDD.
     * @var type INT
     */
    private $timeForThisQuizz;
    
    /**
     * Variable qui contient la place de l'user dans le classement général (par rapport aux autres users et leurs points)
     * N'apparait pas en BDD
     * @var type INT 
     */
    private $generalRank;
    
    /**
     * Variable qui contient la place de l'user dans le classement général (par rapport aux autres users et leurs points)
     * N'apparait pas en BDD
     * @var type INT 
     */
    private $gerenalRank;

    /**
     * @var float
     *
     * @ORM\Column(name="average_time", type="float", nullable=true)
     */
    private $averageTime;

    /**
     * @var float
     *
     * @ORM\Column(name="nb_quizz", type="float", nullable=true)
     */
    private $nbQuizz;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastconnect_at", type="datetime", nullable=true)
     */
    private $lastconnectAt;

    /**
     * @ORM\OneToMany(targetEntity="QuizzResult", mappedBy="user", cascade={"remove", "persist"})
     */
    protected $quizzResults;

    /**
     * @ORM\ManyToMany(targetEntity="Answer", inversedBy="users")
     * @ORM\JoinTable(name="user_answer")
     */
    private $answers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quizzResults = new \Doctrine\Common\Collections\ArrayCollection();
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->points = 0;
        $this->createdAt = new \DateTime();
        $this->averageTime = 0;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fbUid
     *
     * @param string $fbUid
     * @return User
     */
    public function setFbUid($fbUid)
    {
        $this->fbUid = $fbUid;

        return $this;
    }

    /**
     * Get fbUid
     *
     * @return string
     */
    public function getFbUid()
    {
        return $this->fbUid;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set points
     *
     * @param float $points
     * @return User
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return float
     */
    public function getPoints()
    {
        return $this->points;
    }
    
    /**
     * Variable qui contient le score de l'user pour le quizz en cours de consultation (en non en cours de jeu).
     * Utilisé pour lister les amis en fonction de leur score sur ce quizz.
     * N'apparait pas en BDD.
     * @param INT $scoreForThisQuizz 
     * @return USER L'objet User utilisé
     */
    public function setWinPointsForThisQuizz($winPointsForThisQuizz){
	$this->winPointsForThisQuizz = $winPointsForThisQuizz;
	return $this;
    }
    
    /**
     * Variable qui contient le score de l'user pour le quizz en cours de consultation (en non en cours de jeu).
     * Utilisé pour lister les amis en fonction de leur score sur ce quizz.
     * N'apparait pas en BDD.
     * @return INT scoreForThisQuizz
     */
    public function getWinPointsForThisQuizz(){
	return $this->winPointsForThisQuizz;
    }
    
    
    /**
     * Variable qui contient le temps de réponse en secondes au quizz en cours de consultation
     * Utilisé pour lister les amis en fonction de leur score sur ce quizz.
     * N'apparait pas en BDD.
     * @param INT $scoreForThisQuizz 
     * @return USER L'objet User utilisé
     */
    public function setTimeForThisQuizz($timeForThisQuizz){
	$this->timeForThisQuizz = $timeForThisQuizz;
	return $this;
    }
    
    /**
     * Variable qui contient le temps de réponse en secondes au quizz en cours de consultation
     * Utilisé pour lister les amis en fonction de leur score sur ce quizz.
     * N'apparait pas en BDD.
     * @return INT scoreForThisQuizz
     */
    public function getTimeForThisQuizz(){
	return $this->timeForThisQuizz;
    }
    
    /**
     * Variable qui contient la place de l'user dans le classement général (par rapport aux autres users et leurs points)
     * N'apparait pas en BDD
     * @param INT
     * @return User
     */
    public function setGeneralRank($generalRank){
        $this->generalRank = $generalRank;
        return $this;
    }
    
    /**
     * Variable qui contient la place de l'user dans le classement général (par rapport aux autres users et leurs points)
     * N'apparait pas en BDD
     * @return INT
     */
    public function getGeneralRank(){
        return $this->generalRank;
    }

    /**
     * Set averageTime
     *
     * @param float $averageTime
     * @return User
     */
    public function setAverageTime($averageTime)
    {
        $this->averageTime = $averageTime;

        return $this;
    }

    /**
     * Get averageTime
     *
     * @return float
     */
    public function getAverageTime()
    {
        return $this->averageTime;
    }

    /**
     * Set nbQuizz
     *
     * @param float $nbQuizz
     * @return User
     */
    public function setNbQuizz($nbQuizz)
    {
        $this->nbQuizz = $nbQuizz;

        return $this;
    }

    /**
     * Get nbQuizz
     *
     * @return float
     */
    public function getNbQuizz()
    {
        return $this->nbQuizz;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set lastconnectAt
     *
     * @param \DateTime $lastconnectAt
     * @return User
     */
    public function setLastconnectAt($lastconnectAt)
    {
        $this->lastconnectAt = $lastconnectAt;

        return $this;
    }

    /**
     * Get lastconnectAt
     *
     * @return \DateTime
     */
    public function getLastconnectAt()
    {
        return $this->lastconnectAt;
    }

    /**
     * Add quizzResults
     *
     * @param \Metinet\Bundle\FacebookBundle\Entity\QuizzResult $quizzResults
     * @return User
     */
    public function addQuizzResult(\Metinet\Bundle\FacebookBundle\Entity\QuizzResult $quizzResults)
    {
        $this->quizzResults[] = $quizzResults;

        return $this;
    }

    /**
     * Remove quizzResults
     *
     * @param \Metinet\Bundle\FacebookBundle\Entity\QuizzResult $quizzResults
     */
    public function removeQuizzResult(\Metinet\Bundle\FacebookBundle\Entity\QuizzResult $quizzResults)
    {
        $this->quizzResults->removeElement($quizzResults);
    }

    /**
     * Get quizzResults
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuizzResults()
    {
        return $this->quizzResults;
    }

    /**
     * Add answers
     *
     * @param \Metinet\Bundle\FacebookBundle\Entity\Answer $answers
     * @return User
     */
    public function addAnswer(\Metinet\Bundle\FacebookBundle\Entity\Answer $answers)
    {
        $this->answers[] = $answers;

        return $this;
    }

    /**
     * Remove answers
     *
     * @param \Metinet\Bundle\FacebookBundle\Entity\Answer $answers
     */
    public function removeAnswer(\Metinet\Bundle\FacebookBundle\Entity\Answer $answers)
    {
        $this->answers->removeElement($answers);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }
    
    /**
     * Fonction qui va mettre à jour la moyenne de temps de réponse aux quizz de l'user.
     * @param INT $timeToAdd Le nouveau temps en secondes à ajouter à la moyenne.
     */
    public function updateAverageTime($timeToAdd){
	$this->setAverageTime(($this->getAverageTime() * $this->getNbQuizz() + $timeToAdd) / ($this->getNbQuizz() + 1));
    }
}