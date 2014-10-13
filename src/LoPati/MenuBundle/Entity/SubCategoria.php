<?php

namespace LoPati\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use LoPati\MenuBundle\Entity\Translation\SubCategoriaTranslation;
use LoPati\Utilities\Utils;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use LoPati\BlogBundle\Entity\Pagina;
use LoPati\MenuBundle\Entity\Categoria;

/**
 * @ORM\Table(name="subcategoria")
 * @ORM\Entity(repositoryClass="LoPati\MenuBundle\Repository\SubCategoriaRepository")
 * @Gedmo\TranslationEntity(class="LoPati\MenuBundle\Entity\Translation\SubCategoriaTranslation")
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

    protected $slug;

	/** @ORM\Column(type="decimal", precision=3, scale=0) */
	protected $ordre;
	
	/** @ORM\Column(type="boolean", nullable=true) */
	protected $actiu=false;
	
	/** @ORM\Column(type="boolean", nullable=true) */
	protected $llista=false;
	
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
		$this->translations = new ArrayCollection();
	}
	
	/**
	 * Set link
	 *
	 * @param Pagina $link
	 */
	public function setLink(Pagina $link = null)
	{
		$this->link = $link;
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
        $this->slug = Utils::getSlug($nom);
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
     * @param Categoria $categoria
     */
    public function setCategoria(Categoria $categoria = null)
    {
        $this->categoria = $categoria;
    }

    /**
     * Get categoria
     *
     * @return Categoria
     */
    public function getCategoria()
    {
        return $this->categoria;
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
    
    public function __toString()
    {
    	return $this->nom ? $this->nom : '---' . ($this->getCategoria() ? $this->getCategoria()->getNom() ? ' -> ' . $this->getCategoria()->getNom() : '' : '');
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
    	return Utils::getSlug($this->nom);
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
     * @param SubCategoriaTranslation $translation
     */
    public function addTranslation(SubCategoriaTranslation $translation)
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
     *
     * @param Pagina $pagina
     */
    public function addPagines(Pagina $pagina)
    {
    	$this->pagines[] = $pagina;
    }
    
    /**
     * Get pagines
     *
     * @return ArrayCollection
     */
    public function getPagines()
    {
    	return $this->pagines;
    }

    public function setLocale($locale) {
    	$this->locale = $locale;
    }
    
    /**
     * Get locale
     *
     * @return boolean
     */
    public function getLocale() {
    	return $this->locale;
    }
}
