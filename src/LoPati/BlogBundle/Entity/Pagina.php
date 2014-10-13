<?php

namespace LoPati\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LoPati\BlogBundle\Entity\Translation\PaginaTranslation;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use LoPati\Utilities\Utils;
use Gedmo\Mapping\Annotation as Gedmo;
use LoPati\MenuBundle\Entity\Categoria;
use LoPati\MenuBundle\Entity\SubCategoria;

/**
 * @ORM\Table(name="pagina")
 * @ORM\Entity(repositoryClass="LoPati\BlogBundle\Repository\PaginaRepository")
 * @Gedmo\TranslationEntity(class="LoPati\BlogBundle\Entity\Translation\PaginaTranslation")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Pagina {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/** @ORM\Column(type="string", length=50) */
	protected $tipus;

	/** 
	 * @ORM\Column(type="string", length=255) 
	 * @Gedmo\Translatable
	 */
	protected $titol;

	/** 
	 * @ORM\Column(type="string", length=300, nullable=true) 
	 * @Gedmo\Translatable
	 */
	protected $resum = null;

	/** 
	 * @ORM\Column(type="text") 
	 * @Gedmo\Translatable
	 */
	protected $descripcio;
	
	/** @ORM\Column(type="text", nullable=true) */
	protected $links;
	
	/** @ORM\Column(type="boolean", nullable=true) */
	protected $actiu = false;
	
	/** @ORM\Column(type="boolean", nullable=true) */
	protected $portada = false;

	/** @ORM\Column(type="boolean", nullable=true) */
	protected $compartir = false;

	/** @ORM\Column(type="date") */
	protected $data_publicacio;

	/** @ORM\Column(type="boolean", nullable=true) */
	protected $data_visible = false;

	/** @ORM\Column(type="date", nullable=true) */
	protected $data_caducitat = null;

    /** @ORM\Column(type="date", nullable=true) */
    protected $startDate;

    /** @ORM\Column(type="date", nullable=true) */
    protected $endDate;

    /** @ORM\Column(type="boolean", nullable=true) */
	protected $alwaysShowOnCalendar = false;

	/** 
	 * @ORM\Column(type="string", length=255, nullable=true )
	 * @Gedmo\Translatable
	 */
	protected $data_realitzacio = NULL;

	/** 
	 * @ORM\Column(type="string", length=250, nullable=true)
	 * @Gedmo\Translatable
	 */
	protected $lloc = NULL;
	
	/**
	 * @Gedmo\Locale
	 * Used locale to override Translation listener`s locale
	 * this is not a mapped field of entity metadata, just a simple property
	 */
	private $locale;

	/** @ORM\ManyToOne(targetEntity="LoPati\MenuBundle\Entity\Categoria") */
	protected $categoria;

	/** 
	 * @ORM\ManyToOne(targetEntity="LoPati\MenuBundle\Entity\SubCategoria", inversedBy="pagines")
	 * @ORM\JoinColumn(name="subCategoria_id", referencedColumnName="id", nullable=true)
	 */
	protected $subCategoria;
	
	/** @ORM\Column(type="string", length=255, nullable=true) */
	protected $video = NULL;
	
	/** @ORM\Column(type="string", length=255, nullable=true) */
	protected $urlVimeo = NULL;
	
	/** @ORM\Column(type="string", length=255, nullable=true) */
	protected $urlFlickr = NULL;
	
	/**
	 * @Assert\File(
	 *     maxSize="10M",
	 *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
	 * )
	 * @Vich\UploadableField(mapping="imatge", fileNameProperty="imagePetitaName")
	 *
	 * @var File $imagePetita
	 */
	protected $imagePetita;

	/**
	 * @ORM\Column(type="string", length=255, name="image_petita_name", nullable=true)
	 *
	 * @var string $imagePetitaName
	 */
	protected $imagePetitaName;
	
	/**
	 * @Assert\File(
	 *     maxSize="10M",
	 *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
	 * )
	 * @Vich\UploadableField(mapping="imatge", fileNameProperty="imagePetita2Name")
	 *
	 * @var File $imagePetita2
	 */
	protected $imagePetita2;
	
	/**
	 * @ORM\Column(type="string", length=255, name="image_petita2_name", nullable=true)
	 *
	 * @var string $imagePetita2Name
	 */
	protected $imagePetita2Name;
	
	/**
	 * @Assert\File(
	 *     maxSize="10M",
	 *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
	 * )
	 * @Vich\UploadableField(mapping="imatge", fileNameProperty="imageGran1Name")
	 *
	 * @var File $imageGran1
	 */
	protected $imageGran1;
	
	/**
	 * @ORM\Column(type="string", length=255, name="image_gran1_name", nullable=true)
	 *
	 * @var string $imageGran1Name
	 */
	protected $imageGran1Name;
	
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @Gedmo\Translatable
	 */
	protected $peuImageGran1;

	/////////////////////////////
	
	/**
	 * @Assert\File(maxSize="16M")
	 * @Vich\UploadableField(mapping="pdf", fileNameProperty="document1Name")
	 *
	 * @var File $document1
	 */
	private $document1;
	
	/**
	 * @ORM\Column(type="string", length=255, name="document1_name", nullable=true)
	 *
	 * @var string $document1Name
	 */
	protected $document1Name;
	
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $titolDocument1;
	
	/**
	 * @ORM\OneToMany(
	 * 	targetEntity="LoPati\BlogBundle\Entity\Translation\PaginaTranslation",
	 * 	mappedBy="object",
	 * 	cascade={"persist", "remove"}
	 * )
	 * @Assert\Valid(deep = true)
	 */
	private $translations;

    /**
     * @Assert\File(maxSize="16M")
     * @Vich\UploadableField(mapping="pdf", fileNameProperty="document2Name")
     *
     * @var File $document2
     */
    private $document2;

    /**
     * @ORM\Column(type="string", length=255, name="document2_name", nullable=true)
     *
     * @var string $document2Name
     */
    protected $document2Name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $titolDocument2;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    private $arxiu;

	public function getVideo() {
		return $this->video;
	}
	
	public function setVideo($filename) {
        $this->updated  = new \DateTime();
		$this->video=$filename;
	}
	
	public function getUrlVimeo() {
		return $this->urlVimeo;
	}
	
	public function setUrlVimeo($filename) {
		$this->urlVimeo=$filename;
	}
	
	public function getUrlFlickr() {
		return $this->urlFlickr;
	}
	
	public function setUrlFlickr($filename) {
		$this->urlFlickr=$filename;
	}
	
	public function getDocument1() {
		return $this->document1;
	}
	
	public function setDocument1($filename) {
        $this->updated  = new \DateTime();
		$this->document1=$filename;
	}
	
	public function getDocument1Name() {
		return $this->document1Name;
	}
	
	public function setDocument1Name($file) {
        $this->updated  = new \DateTime();
		$this->document1Name=$file;
	}
	
	public function getTitolDocument1() {
		return $this->titolDocument1;
	}
	
	public function setTitolDocument1($file) {
        $this->updated  = new \DateTime();
		$this->titolDocument1=$file;
	}

	////////////////////////////////
	
	public function getLinks() {
		return $this->links;
	}
	
	public function setLinks($file) {
		$this->links=$file;
	}
	
	public function getDocument2() {
		return $this->document2;
	}
	
	public function setDocument2($filename) {
        $this->updated  = new \DateTime();
		$this->document2=$filename;
	}
	
	public function getDocument2Name() {
        $this->updated  = new \DateTime();
		return $this->document2Name;
	}
	
	public function setDocument2Name($file) {
        $this->updated  = new \DateTime();
		$this->document2Name=$file;
	}
	
	public function getTitolDocument2() {
		return $this->titolDocument2;
	}
	
	public function setTitolDocument2($file) {
		$this->titolDocument2=$file;
	}
	
	/////////////////////
	
	public function getPeuImageGran1() {
		return $this->peuImageGran1;
	}
	
	public function setPeuImageGran1($file) {
		$this->peuImageGran1=$file;
	}
	
	public function getImageGran1Name() {
		return $this->imageGran1Name;
	}
	
	public function setImageGran1Name($filename) {
        $this->updated  = new \DateTime();
		$this->imageGran1Name=$filename;
	}
	
	public function getImageGran1() {
		return $this->imageGran1;
	}
	
	public function setImageGran1($file) {
        $this->updated  = new \DateTime();
		$this->imageGran1=$file;
	}
	
	public function getImagePetitaName() {
		return $this->imagePetitaName;
	}
	
	public function setImagePetitaName($filename) {
        $this->updated  = new \DateTime();
		$this->imagePetitaName=$filename;
	}
	
	public function getImagePetita() {
		return $this->imagePetita;
	}

	public function setImagePetita($file) {
        $this->updated  = new \DateTime();
		$this->imagePetita=$file;
	}
	
	public function getImagePetita2() {
		return $this->imagePetita2;
	}
	
	public function setImagePetita2($file) {
        $this->updated  = new \DateTime();
		$this->imagePetita2=$file;
	}
	
	public function getImagePetita2Name() {
		return $this->imagePetita2Name;
	}
	
	public function setImagePetita2Name($filename) {
        $this->updated  = new \DateTime();
		$this->imagePetita2Name=$filename;
	}
	public function __construct() {
		$this->translations = new ArrayCollection();
	}
	
	public function getSlug() {
		return Utils::getSlug($this->titol);
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
	 * @param PaginaTranslation $translation
	 */
	public function addTranslation(PaginaTranslation $translation) {
		if ($translation->getContent()) {
			$translation->setObject($this);
			$this->translations->add($translation);
		}
	}
	
	/**
	 * Remove translation
     *
	 * @param PaginaTranslation $translation
	 */
	public function removeTranslation(PaginaTranslation $translation) {
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
	 * Set tipus
	 *
	 * @param string $tipus
	 */
	public function setTipus($tipus) {
		$this->tipus = $tipus;
	}

	/**
	 * Get tipus
	 * @return string 
	 */
	public function getTipus() {
		return $this->tipus;
	}

	public function setArxiu($tipus) {
		$this->arxiu = $tipus;
	}

	public function getArxiu() {
		return $this->arxiu;
	}
	
	/**
	 * Set titol
	 *
	 * @param string $titol
	 */
	public function setTitol($titol) {
		$this->titol = $titol;
	}

	/**
	 * Get titol
	 *
	 * @return string 
	 */
	public function getTitol() {
		return $this->titol;
	}

	/**
	 * Set resum
	 *
	 * @param string $resum
	 */
	public function setResum($resum) {
		$this->resum = $resum;
	}

	/**
	 * Get resum
	 *
	 * @return string
	 */
	public function getResum() {
		return $this->resum;
	}

	public function __toString() {
		return $this->titol ? $this->getId() . ' · ' . $this->getDataPublicacio()->format('d/m/Y') . ' · ' .$this->getTitol() : '---';
	}

	/**
	 * Set categoria
	 *
	 * @param Categoria $categoria
	 */
	public function setCategoria(Categoria $categoria=null) {
		$this->categoria = $categoria;
	}

	/**
	 * Get categoria
	 *
	 * @return Categoria
	 */
	public function getCategoria() {
		return $this->categoria;
	}

	/**
	 * Set subCategoria
	 *
	 * @param SubCategoria $subCategoria
	 */
	public function setSubCategoria(SubCategoria $subCategoria=null) {
		$this->subCategoria = $subCategoria;
	}

	/**
	 * Get subCategoria
	 *
	 */
	public function getSubCategoria() {
		return $this->subCategoria;
	}

	/**
	 * Set descripcio
	 *
	 * @param string $descripcio
	 */
	public function setDescripcio($descripcio) {
		$this->descripcio = $descripcio;
	}

	/**
	 * Get descripcio
	 *
	 * @return string
	 */
	public function getDescripcio() {
		return $this->descripcio;
	}

	/**
	 * Set actiu
	 *
	 * @param boolean $actiu
	 */
	public function setActiu($actiu) {
		$this->actiu = $actiu;
	}

	/**
	 * Get actiu
	 *
	 * @return boolean 
	 */
	public function getActiu() {
		return $this->actiu;
	}

	/**
	 * Set alwaysShowOnCalendar
	 *
	 * @param boolean $alwaysShowOnCalendar
	 */
	public function setAlwaysShowOnCalendar($alwaysShowOnCalendar) {
		$this->alwaysShowOnCalendar = $alwaysShowOnCalendar;
	}

	/**
	 * Get alwaysShowOnCalendar
	 *
	 * @return boolean 
	 */
	public function getAlwaysShowOnCalendar() {
		return $this->alwaysShowOnCalendar;
	}

	/**
	 * Set portada
	 *
	 * @param boolean $portada
	 */
	public function setPortada($portada) {
		$this->portada = $portada;
	}

	/**
	 * Get portada
	 *
	 * @return boolean 
	 */
	public function getPortada() {
		return $this->portada;
	}

	/**
	 * Set data_publicacio
	 *
	 * @param \DateTime $dataPublicacio
	 */
	public function setDataPublicacio($dataPublicacio) {
		$this->data_publicacio = $dataPublicacio;
	}

	/**
	 * Get data_publicacio
	 *
	 * @return \DateTime
	 */
	public function getDataPublicacio() {
		return $this->data_publicacio;
	}

	/**
	 * Set data_visible
	 *
	 * @param boolean $dataVisible
	 */
	public function setDataVisible($dataVisible) {
		$this->data_visible = $dataVisible;
	}

	/**
	 * Get data_visible
	 *
	 * @return boolean 	
	 */
	public function getDataVisible() {
		return $this->data_visible;
	}

	/**
	 * Set data_caducitat
	 *
	 * @param \DateTime $dataCaducitat
	 */
	public function setDataCaducitat($dataCaducitat) {
		$this->data_caducitat = $dataCaducitat;
	}

	/**
	 * Get data_caducitat
	 *
	 * @return \DateTime
	 */
	public function getDataCaducitat() {
		return $this->data_caducitat;
	}

    /**
	 * Set data_realitzacio
	 *
	 * @param \DateTime $dataRealitzacio
	 */
	public function setDataRealitzacio($dataRealitzacio) {
		$this->data_realitzacio = $dataRealitzacio;
	}

	/**
	 * Get data_realitzacio
	 *
	 * @return \DateTime
	 */
	public function getDataRealitzacio() {
		return $this->data_realitzacio;
	}

	/**
	 * Set lloc
	 *
	 * @param string $lloc
	 */
	public function setLloc($lloc) {
		$this->lloc = $lloc;
	}

	/**
	 * Get lloc
	 *
	 * @return string 
	 */
	public function getLloc() {
		return $this->lloc;
	}

	/**
	 * Set data_caducita
	 *
	 * @param \DateTime $dataCaducitat
	 */
	public function setDataCaducita($dataCaducitat) {
		$this->data_caducitat = $dataCaducitat;
	}

	/**
	 * Set compartir
	 *
	 * @param boolean $compartir
	 * @return Pagina
	 */
	public function setCompartir($compartir) {
		$this->compartir = $compartir;
		return $this;
	}

	/**
	 * Get compartir
	 *
	 * @return boolean 
	 */
	public function getCompartir() {
		return $this->compartir;
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

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return $this
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    public function getTranslatedTitleEs(){
        $i=0;
       while ($i<count($this->translations)){
               if ($this->translations[$i]->getLocale()=='es' && $this->translations[$i]->getField()=='titol'){
                   return $this->translations[$i]->getContent();
               }else{
                   $i++;
               }
       }
        return null;
    }

    public function getTranslatedDescripcioEs()
    {
        $i=0;
        while ($i<count($this->translations)){
            if ($this->translations[$i]->getLocale()=='es' && $this->translations[$i]->getField()=='descripcio'){
                return $this->translations[$i]->getContent();
            }else{
                $i++;
            }
        }
        return null;
    }

    public function getTranslatedDescripcioEn()
    {
        $i=0;
        while ($i<count($this->translations)){
            if ($this->translations[$i]->getLocale()=='en' && $this->translations[$i]->getField()=='descripcio'){
                return $this->translations[$i]->getContent();
            }else{
                $i++;
            }
        }
        return null;
    }

    public function getTranslatedTitleEn()
    {
        $i=0;
        while ($i<count($this->translations)) {
            if ($this->translations[$i]->getLocale()=='en' && $this->translations[$i]->getField()=='titol') {
                return $this->translations[$i]->getContent();
            } else {
                $i++;
            }
        }
        return null;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}