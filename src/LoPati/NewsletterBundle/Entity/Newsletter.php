<?php

namespace LoPati\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LoPati\BlogBundle\Entity;

/**
 * LoPati\NewsletterBundle\Entity\Newsletter
 *
 * @ORM\Table(name="newsletters")
 * @ORM\Entity(repositoryClass="Lopati\NewsletterBundle\Repository\NewsletterRepository")
 */
class Newsletter
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var text $text
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;
    
    /**
     * @var string $created
     *
     * @ORM\Column(name="created", type="date")
     */
    private $created;
    
    /**
     * @var string $sended
     *
     * @ORM\Column(name="sended", type="date", nullable=true)
     */
    private $sended;

    /**
     * @ORM\ManyToMany(targetEntity="LoPati\BlogBundle\Entity\Pagina")
     * @ORM\OrderBy({"titol" = "DESC"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     * 
     */
    protected $pagines;
    
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->sended = null;
       
        	$this->pagines = new \Doctrine\Common\Collections\ArrayCollection();
        	
        
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set text
     *
     * @param text $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get text
     *
     * @return text 
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * Set created
     *
     * @param date $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return date 
     */
    public function getCreated()
    {
        return $this->created;
    }
    
    public function setSended($sended)
    {
        $this->sended = $sended;
    }

    public function getSended()
    {
        return $this->sended;
    }
    
    public function setPagines($pagines)
    {
    	$this->pagines = $pagines;
    }
    
    /**
     * Get category
     *
     * @return Category
     */
    public function getPagines()
    {
    	return $this->pagines;
    }
    public function addPagines(\LoPati\BlogBundle\Entity\Pagina $pagina)
    {
    	$this->pagines[] = $pagina;
    }
}