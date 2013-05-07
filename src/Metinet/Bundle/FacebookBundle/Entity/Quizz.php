<?php

namespace Metinet\Bundle\FacebookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quizz
 *
 * @ORM\Table(name="quizz")
 * @ORM\Entity(repositoryClass="Metinet\Bundle\FacebookBundle\Repository\QuizzRepository")
 */
class Quizz
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
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="short_desc", type="string", length=255)
     */
    private $shortDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="long_desc", type="text", nullable=true)
     */
    private $longDesc;

    /**
     * @var integer
     *
     * @ORM\Column(name="win_points", type="integer")
     */
    private $winPoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="average_time", type="integer")
     */
    private $averageTime;

    /**
     * @var string
     *
     * @ORM\Column(name="txt_win_1", type="string", length=255)
     */
    private $txtWin1;

    /**
     * @var string
     *
     * @ORM\Column(name="txt_win_3", type="string", length=255)
     */
    private $txtWin3;

    /**
     * @var string
     *
     * @ORM\Column(name="txt_win_2", type="string", length=255)
     */
    private $txtWin2;

    /**
     * @var string
     *
     * @ORM\Column(name="txt_win_4", type="string", length=255)
     */
    private $txtWin4;

    /**
     * @var string
     *
     * @ORM\Column(name="share_wall_title", type="string", length=255)
     */
    private $shareWallTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="share_wall_desc", type="string", length=255)
     */
    private $shareWallDesc;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_promoted", type="boolean")
     */
    private $isPromoted;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer")
     */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity="QuizzResult", mappedBy="quizz", cascade={"remove", "persist"})
     */
    protected $quizzResults;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="quizz", cascade={"remove", "persist"})
     */
    protected $questions;

    /**
     * @ORM\ManyToOne(targetEntity="Theme", inversedBy="quizzes")
     * @ORM\JoinColumn(name="theme_id", referencedColumnName="id")
     */
    protected $theme;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quizzResults = new \Doctrine\Common\Collections\ArrayCollection();
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->state = 0;
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
     * @return Quizz
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

    /**
     * Set shortDesc
     *
     * @param string $shortDesc
     * @return Quizz
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
     * @return Quizz
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
     * Set winPoints
     *
     * @param integer $winPoints
     * @return Quizz
     */
    public function setWinPoints($winPoints)
    {
        $this->winPoints = $winPoints;

        return $this;
    }

    /**
     * Get winPoints
     *
     * @return integer
     */
    public function getWinPoints()
    {
        return $this->winPoints;
    }

    /**
     * Set averageTime
     *
     * @param integer $averageTime
     * @return Quizz
     */
    public function setAverageTime($averageTime)
    {
        $this->averageTime = $averageTime;

        return $this;
    }

    /**
     * Get averageTime
     *
     * @return integer
     */
    public function getAverageTime()
    {
        return $this->averageTime;
    }

    /**
     * Set txtWin1
     *
     * @param string $txtWin1
     * @return Quizz
     */
    public function setTxtWin1($txtWin1)
    {
        $this->txtWin1 = $txtWin1;

        return $this;
    }

    /**
     * Get txtWin1
     *
     * @return string
     */
    public function getTxtWin1()
    {
        return $this->txtWin1;
    }

    /**
     * Set txtWin3
     *
     * @param string $txtWin3
     * @return Quizz
     */
    public function setTxtWin3($txtWin3)
    {
        $this->txtWin3 = $txtWin3;

        return $this;
    }

    /**
     * Get txtWin3
     *
     * @return string
     */
    public function getTxtWin3()
    {
        return $this->txtWin3;
    }

    /**
     * Set txtWin2
     *
     * @param string $txtWin2
     * @return Quizz
     */
    public function setTxtWin2($txtWin2)
    {
        $this->txtWin2 = $txtWin2;

        return $this;
    }

    /**
     * Get txtWin2
     *
     * @return string
     */
    public function getTxtWin2()
    {
        return $this->txtWin2;
    }

    /**
     * Set txtWin4
     *
     * @param string $txtWin4
     * @return Quizz
     */
    public function setTxtWin4($txtWin4)
    {
        $this->txtWin4 = $txtWin4;

        return $this;
    }

    /**
     * Get txtWin4
     *
     * @return string
     */
    public function getTxtWin4()
    {
        return $this->txtWin4;
    }

    /**
     * Set shareWallTitle
     *
     * @param string $shareWallTitle
     * @return Quizz
     */
    public function setShareWallTitle($shareWallTitle)
    {
        $this->shareWallTitle = $shareWallTitle;

        return $this;
    }

    /**
     * Get shareWallTitle
     *
     * @return string
     */
    public function getShareWallTitle()
    {
        return $this->shareWallTitle;
    }

    /**
     * Set shareWallDesc
     *
     * @param string $shareWallDesc
     * @return Quizz
     */
    public function setShareWallDesc($shareWallDesc)
    {
        $this->shareWallDesc = $shareWallDesc;

        return $this;
    }

    /**
     * Get shareWallDesc
     *
     * @return string
     */
    public function getShareWallDesc()
    {
        return $this->shareWallDesc;
    }

    /**
     * Set isPromoted
     *
     * @param boolean $isPromoted
     * @return Quizz
     */
    public function setIsPromoted($isPromoted)
    {
        $this->isPromoted = $isPromoted;

        return $this;
    }

    /**
     * Get isPromoted
     *
     * @return boolean
     */
    public function getIsPromoted()
    {
        return $this->isPromoted;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Quizz
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
     * Set state
     *
     * @param integer $state
     * @return Quizz
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }


    /**
     * Add quizzResults
     *
     * @param \Metinet\Bundle\FacebookBundle\Entity\QuizzResult $quizzResults
     * @return Quizz
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
     * Add questions
     *
     * @param \Metinet\Bundle\FacebookBundle\Entity\Question $questions
     * @return Quizz
     */
    public function addQuestion(\Metinet\Bundle\FacebookBundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;

        return $this;
    }

    /**
     * Remove questions
     *
     * @param \Metinet\Bundle\FacebookBundle\Entity\Question $questions
     */
    public function removeQuestion(\Metinet\Bundle\FacebookBundle\Entity\Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set theme
     *
     * @param \Metinet\Bundle\FacebookBundle\Entity\Theme $theme
     * @return Quizz
     */
    public function setTheme(\Metinet\Bundle\FacebookBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return \Metinet\Bundle\FacebookBundle\Entity\Theme
     */
    public function getTheme()
    {
        return $this->theme;
    }
}