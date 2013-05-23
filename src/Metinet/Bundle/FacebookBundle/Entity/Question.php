<?php

namespace Metinet\Bundle\FacebookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="Metinet\Bundle\FacebookBundle\Repository\QuestionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Question
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    private $picture;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    /**
     * @ORM\ManyToOne(targetEntity="Quizz", inversedBy="questions")
     * @ORM\JoinColumn(name="quizz_id", referencedColumnName="id")
     */
    protected $quizz;

    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question", cascade={"remove", "persist"})
     */
    protected $answers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Question
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return Question
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
     * Set quizz
     *
     * @param \Metinet\Bundle\FacebookBundle\Entity\Quizz $quizz
     * @return Question
     */
    public function setQuizz(\Metinet\Bundle\FacebookBundle\Entity\Quizz $quizz = null)
    {
        $this->quizz = $quizz;

        return $this;
    }

    /**
     * Get quizz
     *
     * @return \Metinet\Bundle\FacebookBundle\Entity\Quizz
     */
    public function getQuizz()
    {
        return $this->quizz;
    }

    /**
     * Add answers
     *
     * @param \Metinet\Bundle\FacebookBundle\Entity\Answer $answers
     * @return Question
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
     * Retourne le nombre de fois que le quizz a été joué.
     * /!\ Variable opionelle non remplie automatiquement. Sert plus de variable de transport de données à la vue TWIG.
     */
    public function getNbTimesPlayed(){
	return $this->nbTimesPlayed;
    }
    
    /**
     * Set le nombre de fois que le quizz a été joué.
     * Sert plus de variable de transport de données à la vue TWIG.
     * N'apparait pas en base.
     */
    public function setNbTimesPlayed($nbTimesPlayed){
	$this->nbTimesPlayed = $nbTimesPlayed;
	return $this;
    }
    
     public function getAbsolutePath()
    {
        return null === $this->picture ? null : $this->getUploadRootDir().'/'.$this->picture;
    }
    public function getWebPath()
    {
        return null === $this->picture ? null : $this->getUploadDir().'/'.$this->picture;
    }

     protected function getUploadRootDir() {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../Resources/public/uploads/pictures/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'question';
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        
         if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
            $this->picture = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
            
     if (null === $this->file) {
            return;
        }

        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a
        print_r($this->picture);
        
        $this->file->move($this->getUploadRootDir(), $this->picture);

        unset($this->file);
    }
    
    

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
         if ($this->file == $this->getAbsolutePath()) {
            unlink($this->file);
        }
    }
}