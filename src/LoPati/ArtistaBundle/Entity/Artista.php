<?php
namespace LoPati\ArtistaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use LoPati\MenuBundle\Util\Util;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="LoPati\ArtistaBundle\Repository\ArtistaRepository")
 * @Gedmo\TranslationEntity(class="LoPati\ArtistaBundle\Entity\Translation\ArtistaTranslation")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Artista {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
     * @ORM\Column(type="string", length=50)
     */
	protected $name;

	/** 
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @Gedmo\Translatable
	 */
	protected $summary;

	/** 
	 * @ORM\Column(type="text", length=4000, nullable=true)
	 * @Gedmo\Translatable
	 */
	protected $description;

	/**
     * @ORM\Column(type="boolean")
     */
	protected $active = TRUE;

    /**
     * @ORM\Column(type="integer")
     */
    protected $position;
	
	/**
	 * @Gedmo\Locale
	 * Used locale to override Translation listener`s locale
	 * this is not a mapped field of entity metadata, just a simple property
	 */
	private $locale;

	/**
	 * @Assert\File(
	 *     maxSize="10M",
	 *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
	 * )
	 * @Vich\UploadableField(mapping="artista", fileNameProperty="image1")
	 */
	protected $image1File;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $image1;

    /**
     * @Assert\File(
     *     maxSize="10M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="artista", fileNameProperty="image1")
     */
    protected $image2File;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image2;

    /**
     * @Assert\File(
     *     maxSize="10M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="artista", fileNameProperty="image1")
     */
    protected $image3File;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image3;

    /**
     * @Assert\File(
     *     maxSize="10M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="artista", fileNameProperty="image1")
     */
    protected $image4File;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image4;

    /**
     * @Assert\File(
     *     maxSize="10M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="artista", fileNameProperty="image1")
     */
    protected $image5File;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image5;

    /**
	 * @ORM\OneToMany(
	 * 	targetEntity="LoPati\ArtistaBundle\Entity\Translation\ArtistaTranslation",
	 * 	mappedBy="object",
	 * 	cascade={"persist", "remove"}
	 * )
	 * @Assert\Valid(deep = true)
	 */
	private $translations;
	

	public function __construct() {
		$this->translations = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	public function getSlug() {
		return Util::getSlug($this->titol);
	}
	
	/**
	 * Set translations
	 * @param ArrayCollection $translations
	 * @return Product
	 */
	public function setTranslations($translations) {
		$this->translations = $translations;
		return $this;
	}
	
	/**
	 * Get translations
	 * @return ArrayCollection
	 */
	public function getTranslations() {
		return $this->translations;
	}
	
	/**
	 * Add translation
	 * @param ProductTranslation
	 */
	public function addTranslation($translation) {
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
	
	/**
	 * Get id
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $image1
     */
    public function setImage1($image1)
    {
        $this->image1 = $image1;
    }

    /**
     * @return mixed
     */
    public function getImage1()
    {
        return $this->image1;
    }

    /**
     * @param mixed $image1File
     */
    public function setImage1File($image1File)
    {
        $this->image1File = $image1File;
    }

    /**
     * @return mixed
     */
    public function getImage1File()
    {
        return $this->image1File;
    }

    /**
     * @param mixed $image2
     */
    public function setImage2($image2)
    {
        $this->image2 = $image2;
    }

    /**
     * @return mixed
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * @param mixed $image2File
     */
    public function setImage2File($image2File)
    {
        $this->image2File = $image2File;
    }

    /**
     * @return mixed
     */
    public function getImage2File()
    {
        return $this->image2File;
    }

    /**
     * @param mixed $image3
     */
    public function setImage3($image3)
    {
        $this->image3 = $image3;
    }

    /**
     * @return mixed
     */
    public function getImage3()
    {
        return $this->image3;
    }

    /**
     * @param mixed $image3File
     */
    public function setImage3File($image3File)
    {
        $this->image3File = $image3File;
    }

    /**
     * @return mixed
     */
    public function getImage3File()
    {
        return $this->image3File;
    }

    /**
     * @param mixed $image4
     */
    public function setImage4($image4)
    {
        $this->image4 = $image4;
    }

    /**
     * @return mixed
     */
    public function getImage4()
    {
        return $this->image4;
    }

    /**
     * @param mixed $image4File
     */
    public function setImage4File($image4File)
    {
        $this->image4File = $image4File;
    }

    /**
     * @return mixed
     */
    public function getImage4File()
    {
        return $this->image4File;
    }

    /**
     * @param mixed $image5
     */
    public function setImage5($image5)
    {
        $this->image5 = $image5;
    }

    /**
     * @return mixed
     */
    public function getImage5()
    {
        return $this->image5;
    }

    /**
     * @param mixed $image5File
     */
    public function setImage5File($image5File)
    {
        $this->image5File = $image5File;
    }

    /**
     * @return mixed
     */
    public function getImage5File()
    {
        return $this->image5File;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }



	public function __toString() {
		return $this->getSlug();
	}

}