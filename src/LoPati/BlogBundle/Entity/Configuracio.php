<?php

namespace LoPati\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LoPati\BlogBundle\Entity\Translation\ConfiguracioTranslation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="configuracio")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="LoPati\BlogBundle\Entity\Translation\ConfiguracioTranslation")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Configuracio {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;
	
	/** @ORM\Column(type="text", nullable=true) */
	protected $adresa;
		
	/** 
     * @ORM\Column(type="text", nullable=true) 
	 * @Gedmo\Translatable
	 */
	protected $horari;
		
	/** 
     * @ORM\Column(type="text", nullable=true) 
	 * @Gedmo\Translatable
	 */
	protected $organitza;

	/** 
     * @ORM\Column(type="text", nullable=true)
	 * @Gedmo\Translatable
	 */
	protected $colabora;
	
	/**
	 * @ORM\OneToMany(
	 * 	targetEntity="LoPati\BlogBundle\Entity\Translation\ConfiguracioTranslation",
	 * 	mappedBy="object",
	 * 	cascade={"persist", "remove"}
	 * )
	 * @Assert\Valid(deep = true)
	 */
	private $translations;
	
	public function __construct() {
		$this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set adresa
     *
     * @param string $adresa
     * @return Configuracio
     */
    public function setAdresa($adresa)
    {
        $this->adresa = $adresa;
    
        return $this;
    }

    /**
     * Get adresa
     *
     * @return string 
     */
    public function getAdresa()
    {
        return $this->adresa;
    }

    /**
     * Set horari
     *
     * @param string $horari
     * @return Configuracio
     */
    public function setHorari($horari)
    {
        $this->horari = $horari;
    
        return $this;
    }

    /**
     * Get horari
     *
     * @return string 
     */
    public function getHorari()
    {
        return $this->horari;
    }

    /**
     * Set organitza
     *
     * @param string $organitza
     * @return Configuracio
     */
    public function setOrganitza($organitza)
    {
        $this->organitza = $organitza;
    
        return $this;
    }

    /**
     * Get organitza
     *
     * @return string 
     */
    public function getOrganitza()
    {
        return $this->organitza;
    }

    /**
     * Set colabora
     *
     * @param string $colabora
     * @return Configuracio
     */
    public function setColabora($colabora)
    {
        $this->colabora = $colabora;
    
        return $this;
    }

    /**
     * Get colabora
     *
     * @return string 
     */
    public function getColabora()
    {
        return $this->colabora;
    }
    
    /**
     * Set translations
     *
     * @param ArrayCollection $translations
     *
     * @return $this
     */
    public function setTranslations($translations) {
    	$this->translations = $translations;

    	return $this;
    }
    
    /**
     * Get translations
     *
     * @return ArrayCollection
     */
    public function getTranslations() {
    	return $this->translations;
    }
    
    /**
     * Add translation
     *
     * @param ConfiguracioTranslation $translation
     */
    public function addTranslation(ConfiguracioTranslation $translation) {
    	if ($translation->getContent()) {
    		$translation->setObject($this);
    		$this->translations->add($translation);
    	}
    }
    
    /**
     * Remove translation
     * @param ProductTranslation
     */
    public function removeTranslation($translation) {
    	$this->translations->removeElement($translation);
    }

    public function __toString(){
    	return "Configuració";
    	
    }
}