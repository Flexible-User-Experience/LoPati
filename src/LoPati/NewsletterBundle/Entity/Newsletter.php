<?php

namespace LoPati\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use LoPati\BlogBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\EventDispatcher\Event;

/**
 * LoPati\NewsletterBundle\Entity\Newsletter
 *
 * @ORM\Table(name="newsletters")
 * @ORM\Entity(repositoryClass="LoPati\NewsletterBundle\Repository\NewsletterRepository")
 * @UniqueEntity("numero")
 * @UniqueEntity("dataNewsletter")
 */
class Newsletter
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     * 
     */
    private $id;

    /**
     * @var integer $numero
     *
     * @ORM\Column(name="numero", type="integer", unique=true)
     * 
     *
     */
    private $numero;
    

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
    private $iniciEnviament=null;
    
    
    /**
     * @var string $fiEnviament
     *
     * @ORM\Column(name="fiEnviament", type="datetime", nullable=true)
     */
    private $fiEnviament=null;
	
    
    /**
     *
     * @ORM\Column(name="estat", type="string", length=15, nullable=true)
     */
    private $estat=null;
    
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
     * @ORM\OrderBy({"id" = "DESC"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     * 
     */
    protected $pagines;
    
    public function __construct()
    {
       
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
	
    public function getNumero()
    {
    	return $this->numero;
    }
    
    public function setNumero($created)
    {
    	$this->numero = $created;
    }
    
    public function setId($created)
    {
    	$this->id = $created;
    }
    
    public function setDataNewsletter($created)
    {
    	$this->dataNewsletter = $created;
    }
    

    public function getDataNewsletter()
    {
    	return $this->dataNewsletter;
    }
    
    


    public function setIniciEnviament($iniciEnviament)
    {
        $this->iniciEnviament = $iniciEnviament;
    }


    public function getIniciEnviament()
    {
        return $this->iniciEnviament;
    }
    
    public function setFiEnviament($fiEnviament)
    {
        $this->fiEnviament = $fiEnviament;
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
    
    public function __toString(){
    	//date('F d, Y h:i:s A', strtotime($this->dataNewsletter;))
    	return "Newsletter nº".$this->numero. " id:" .$this->id;
    
    }
}