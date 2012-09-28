<?php
namespace LoPati\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LoPati\MenuBundle\Util\Util;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="LoPati\MenuBundle\Entity\Translation\SubCategoriaTranslation")
 * @ORM\Entity(repositoryClass="LoPati\MenuBundle\Repository\SubCategoriaRepository")
 */

class SubCategoria {

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
	protected $actiu=FALSE;	
	
	/** @ORM\Column(type="boolean", nullable=true) */
	protected $llista=FALSE;
	
	/** @ORM\ManyToOne(targetEntity="Categoria", inversedBy="subCategoria") */
	protected $categoria;
	
	/** @ORM\OneToOne(targetEntity="LoPati\BlogBundle\Entity\Pagina") */
	protected $link;
	
	/** 
	 * @ORM\OneToMany(targetEntity="LoPati\BlogBundle\Entity\Pagina", mappedBy="subCategoria", cascade={"persist", "remove"} )
	 * @ORM\OrderBy({"data_publicacio" = "ASC"})
	 */
	protected $pagines;
	
	/**
	 * @Gedmo\Locale
	 * Used locale to override Translation listener`s locale
	 * this is not a mapped field of entity metadata, just a simple property
	 */
	private $locale;
	/**
	 * @ORM\OneToMany(
	 * 	targetEntity="LoPati\MenuBundle\Entity\Translation\SubCategoriaTranslation",
	 * 	mappedBy="object",
	 * 	cascade={"persist", "remove"}
	 * )
	 * @Assert\Valid(deep = true)
	 */
	private $translations;
	
	public function __construct()
	{
		$this->translations = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
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
        $this->slug = Util::getSlug($nom);
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
 
    /**
     * Set categoria
     *
     * @param LoPati\MenuBundle\Entity\Categoria $categoria
     */
    public function setCategoria(\LoPati\MenuBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;
    }

    /**
     * Get categoria
     *
     * @return LoPati\MenuBundle\Entity\Categoria 
     */
    public function getCategoria()
    {
        return $this->categoria;
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
    
    public function __toString()
    {
    	return $this->getNom()." -> ".$this->getCategoria()->getNom();
    }

    /**
     * Set llista
     *
     * @param boolean $llista
     */
    public function setLlista($llista)
    {
        $this->llista = $llista;
    }

    /**
     * Get llista
     *
     * @return boolean 
     */
    public function getLlista()
    {
        return $this->llista;
    }
    
    public function getSlug()
    {
    	return Util::getSlug($this->nom);
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
    
    /**
     * Add pagines
     * @param LoPati\BlogBundle\Entity\Pagine $pagines
     */
    public function addPagines(\LoPati\BlogBundle\Entity\Pagina $pagines)
    {
    	$this->pagines[] = $pagines;
    }
    
    /**
     * Get pagines
     * @return Doctrine\Common\Collections\Collection
     */
    public function getPagines()
    {
    	return $this->pagines;
    }
    public function setLocale($locale) {
    	$this->locale = $locale;
    
    }
    
    /**
     * Get compartir
     *
     * @return boolean
     */
    public function getLocale() {
    	return $this->locale;
    }
}