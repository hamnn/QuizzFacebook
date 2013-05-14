<?php

namespace Metinet\Bundle\FacebookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Theme
 *
 * @ORM\Table(name="theme")
 * @ORM\Entity(repositoryClass="Metinet\Bundle\FacebookBundle\Repository\ThemeRepository")
 */
class Theme
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
    
    // propriété utilisé temporairement pour la suppression
    private $filenameForRemove;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    /**
     * @var string
     *
     * @ORM\Column(name="short_desc", type="string", length=255, nullable=true)
     */
    private $shortDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="long_desc", type="text", nullable=true)
     */
    private $longDesc;

    /**
     * @ORM\OneToMany(targetEntity="Quizz", mappedBy="theme", cascade={"remove", "persist"})
     */
    protected $quizzes;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quizzes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Theme
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
     * Set shortDesc
     *
     * @param string $shortDesc
     * @return Theme
     */
    public function setShortDesc($shortDesc)
    {
        $this->shortDesc = $shortDesc;

        return $this;
    }

    /**
     * Get shortDesc
     *
     * @return string
     */
    public function getShortDesc()
    {
        return $this->shortDesc;
    }

    /**
     * Set longDesc
     *
     * @param string $longDesc
     * @return Theme
     */
    public function setLongDesc($longDesc)
    {
        $this->longDesc = $longDesc;

        return $this;
    }

    /**
     * Get longDesc
     *
     * @return string
     */
    public function getLongDesc()
    {
        return $this->longDesc;
    }

    /**
     * Add quizzes
     *
     * @param \Metinet\Bundle\FacebookBundle\Entity\Quizz $quizzes
     * @return Theme
     */
    public function addQuizze(\Metinet\Bundle\FacebookBundle\Entity\Quizz $quizzes)
    {
        $this->quizzes[] = $quizzes;

        return $this;
    }

    /**
     * Remove quizzes
     *
     * @param \Metinet\Bundle\FacebookBundle\Entity\Quizz $quizzes
     */
    public function removeQuizze(\Metinet\Bundle\FacebookBundle\Entity\Quizz $quizzes)
    {
        $this->quizzes->removeElement($quizzes);
    }

    /**
     * Get quizzes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuizzes()
    {
        return $this->quizzes;
    }
    
     /**
     * Set picture
     *
     * @param string $picture
     * @return Quizz
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
    
     public function getAbsolutePath()
    {
        return null === $this->picture ? null : $this->getUploadRootDir().'/theme'.$this->id.'.'.$this->picture;
    }
    public function getWebPath()
    {
        return null === $this->picture ? null : $this->getUploadDir().'/theme'.$this->id.'.'.$this->picture;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'bundles/metinetfacebook/uploads/pictures';
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            $this->picture = $this->file->guessExtension();
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

        // vous devez lancer une exception ici si le fichier ne peut pas
        // être déplacé afin que l'entité ne soit pas persistée dans la
        // base de données comme le fait la méthode move() de UploadedFile
        $this->file->move($this->getUploadRootDir(), 'quizz'.$this->id.'.'.$this->file->guessExtension());

        unset($this->file);
    }
    
    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
         if ($this->filenameForRemove) {
            unlink($this->filenameForRemove);
        }
    }
}