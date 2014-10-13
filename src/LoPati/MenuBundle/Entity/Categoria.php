<?php

namespace LoPati\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LoPati\MenuBundle\Entity\Translation\CategoriaTranslation;
use LoPati\Utilities\Utils;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use LoPati\BlogBundle\Entity\Pagina;
use LoPati\MenuBundle\Entity\SubCategoria;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="categoria")
 * @ORM\Entity(repositoryClass="LoPati\MenuBundle\Repository\CategoriaRepository")
 * @Gedmo\TranslationEntity(class="LoPati\MenuBundle\Entity\Translation\CategoriaTranslation")
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
     * @param Pagina $link
     */
    public function setLink(Pagina $link=null)
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
		$this->subCategoria = new ArrayCollection();
		$this->translations = new ArrayCollection();
	}
	
	/**
	 * Get link
	 *
	 * @return Pagina
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
    	return Utils::getSlug($this->nom);
    }
    
    /**
     * Set ordre
     *
     * @param int $ordre
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    }

    /**
     * Get ordre
     *
     * @return int
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
    	return $this->nom ? $this->nom : '---';
    }
    
    /**
     * Add subCategoria
     *
     * @param SubCategoria $subCategoria
     */
    public function addsubCategoria(SubCategoria $subCategoria)
    {
        $this->subCategoria[] = $subCategoria;
    }

    /**
     * Get subCategoria
     *
     * @return ArrayCollection
     */
    public function getSubCategoria()
    {
        return $this->subCategoria;
    }

    /**
     * Set translations
     *
     * @param ArrayCollection $translations
     *
     * @return $this
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
     * @param CategoriaTranslation $translation
     */
    public function addTranslation(CategoriaTranslation $translation)
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
