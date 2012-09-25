<?php

namespace LoPati\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LoPati\BlogBundle\Entity;

/**
 * LoPati\NewsletterBundle\Entity\Newsletter
 *
 * @ORM\Table(name="newsletters")
 * @ORM\Entity
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
     * @var string $dataNewsletter
     *
     * @ORM\Column(name="dataNewsletter", type="date")
     */
    private $dataNewsletter;
    
    /**
     * @var string $iniciEnviament
     *
     * @ORM\Column(name="iniciEnviament", type="datetime", nullable=true)
     */
    private $iniciEnviament;
    
    
    /**
     * @var string $fiEnviament
     *
     * @ORM\Column(name="fiEnviament", type="datetime", nullable=true)
     */
    private $fiEnviament;
	
    
    /**
     *
     * @ORM\Column(name="estat", type="string", length=15, nullable=true)
     */
    private $estat="Esperant";
    
    /**
     *
     * @ORM\Column(name="subscrits", type="integer", nullable=true)
     */
    private $subscrits;
    
    /**
     *
     * @ORM\Column(name="enviats", type="integer", nullable=true)
     */
    private $enviats;
    
    /**
     * @var string $sended
     *
     * @ORM\Column(name="test", type="boolean", nullable=true)
     */
    private $test=FALSE;
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

    public function setDataNewsletter($created)
    {
    	$this->dataNewsletter = $created;
    }
    

    public function getDataNewsletter()
    {
    	return $this->dataNewsletter;
    }
    
    


    public function setIniciEnviament($created)
    {
        $this->iniciEnviament = $created;
    }


    public function getIniciEnviament()
    {
        return $this->iniciEnviament;
    }
    
    public function setFiEnviament($sended)
    {
        $this->fiEnviament = $sended;
    }

    public function getFiEnviament()
    {
        return $this->fiEnviament;
    }
    
    public function setEstat($estat)
    {
    	$this->estat = $estat;
    }
    
    public function getEstat()
    {
    	return $this->estat;
    }
    
    public function setSubscrits($estat)
    {
    	$this->subscrits = $estat;
    }
    
    public function getSubscrits()
    {
    	return $this->subscrits;
    }
    
    public function setEnviats($estat)
    {
    	$this->enviats = $estat;
    }
    
    public function getEnviats()
    {
    	return $this->enviats;
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
    
    public function setTest($created)
    {
    	$this->test = $created;
    }
    
    /**
     * Get created
     *
     * @return date
     */
    public function getTest()
    {
    	return $this->test;
    }
    
    
}