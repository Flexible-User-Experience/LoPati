<?php
namespace LoPati\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LoPati\MenuBundle\Util\Util;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\EventDispatcher\Event;

/**
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="LoPati\MenuBundle\Entity\Translation\CategoriaTranslation")
 * @ORM\Entity(repositoryClass="LoPati\MenuBundle\Repository\CategoriaRepository")
 */

class Categoria {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;
	
	/** @ORM\Column(type="string", length=100) 
	 * @Gedmo\Translatable
	 */
	protected $nom;
	
	/** @ORM\Column(type="decimal", precision=3, scale=0) */
	protected $ordre;

	/** @ORM\Column(type="boolean", nullable=true) */
	protected $actiu;
	
	/** @ORM\Column(type="boolean", nullable=true) */
	protected $arxiu;
	
	/** 
	 * @ORM\OneToMany(targetEntity="LoPati\MenuBundle\Entity\SubCategoria", mappedBy="categoria", cascade={"persist", "remove"} )
	 * @ORM\OrderBy({"actiu" = "DESC", "ordre" = "ASC"})
	 */
	protected $subCategoria; 
    
	/** @ORM\OneToOne(targetEntity="LoPati\BlogBundle\Entity\Pagina",cascade={"persist", "remove"})
	 */
	protected $link;
	
	/**
	 * Set link
	 *
	 * @param LoPati\BlogBundle\Entity\Pagina $link
	 */
	public function setLink(\LoPati\BlogBundle\Entity\Pagina $link=null)
	{
		$this->link = $link;
	}
	
	/**
	 * @ORM\OneToMany(
	 * 	targetEntity="LoPati\MenuBundle\Entity\Translation\CategoriaTranslation",
	 * 	mappedBy="object",
	 * 	cascade={"persist", "remove"}
	 * )
	 * @Assert\Valid(deep = true)
	 */
	private $translations;
	
	public function __construct()
	{
		$this->subCategoria = new \Doctrine\Common\Collections\ArrayCollection();
		$this->translations = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	/**
	 * Get link
	 *
	 * @return LoPati\BlogBundle\Entity\Pagina
	 */
	public function getLink()
	{
		return $this->link;
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
     * Set nom
     *
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
       
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    public function getSlug()
    {
    	return Util::getSlug($this->nom);
    }
    
    /**
     * Set ordre
     *
     * @param decimal $ordre
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    }

    /**
     * Get ordre
     *
     * @return decimal 
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set actiu
     *
     * @param boolean $actiu
     */
    public function setActiu($actiu)
    {
        $this->actiu = $actiu;
    }

    /**
     * Get actiu
     *
     * @return boolean 
     */
    public function getActiu()
    {
        return $this->actiu;
    }
    
    
  
    public function setArxiu($arxiu=0)
    {
    	$this->arxiu = $arxiu;
    }
    

    public function getArxiu()
    {
    	return $this->arxiu;
    }
    
    public function __toString()
    {
    	return $this->nom;
    }
    
    /**
     * Add subCategoria
     *
     * @param LoPati\MenuBundle\Entity\SubCategoria $subCategoria
     */
    public function addsubCategoria(\LoPati\MenuBundle\Entity\SubCategoria $subCategoria)
    {
        $this->subCategoria[] = $subCategoria;
    }

    /**
     * Get subCategoria
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSubCategoria()
    {
        return $this->subCategoria;
    }

    /**
     * Set translations
     *
     * @param ArrayCollection $translations
     * @return Product
     */
    public function setTranslations($translations)
    {
    	$this->translations = $translations;
    	return $this;
    }
    
    /**
     * Get translations
     *
     * @return ArrayCollection
     */
    public function getTranslations()
    {
    	return $this->translations;
    }
    
    /**
     * Add translation
     *
     * @param ProductTranslation
     */
    public function addTranslation($translation)
    {
    	if ($translation->getContent()) {
    		$translation->setObject($this);
    		$this->translations->add($translation);
    	}	
    }
    
    /**
     * Remove translation
     *
     * @param ProductTranslation
     */
    public function removeTranslation($translation)
    {
    	$this->translations->removeElement($translation);
    }

}