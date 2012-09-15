<?php

namespace LoPati\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use LoPati\MenuBundle\Util\Util;
/**
 * LoPati\BlogBundle\Entity\Product
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LoPati\BlogBundle\Repository\ProductRepository")
 * @Gedmo\TranslationEntity(class="LoPati\BlogBundle\Entity\Translation\ProductTranslation")
 */
class Product
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
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Gedmo\Translatable
     */
    private $title;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text")
     * @Gedmo\Translatable
     */
    private $description;
	
	    
    /**
     * @var string $prova
     *
     * @ORM\Column(name="prova", type="text")
     */
    private $prova;
    
    
    /**
     * @ORM\OneToMany(
     * 	targetEntity="LoPati\BlogBundle\Entity\Translation\ProductTranslation",
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
     * Set prova
     *
     * @param string $description
     * @return Product
     */
    public function setProva($prova)
    {
    	$this->prova = $prova;
    	return $this;
    }
    
    /**
     * Get prova
     *
     * @return string
     */
    public function getProva()
    {
    	return $this->prova;
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
     * @return Product
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
    public function getNomslug()
    {
    	return Util::getSlug($this->title);
    	 
    }
    /**
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get CategoryNews
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
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
    public function __toString(){
    	
    	return $this->getTitle();
    }

}