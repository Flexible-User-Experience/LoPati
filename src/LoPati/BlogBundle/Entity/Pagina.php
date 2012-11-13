<?php
namespace LoPati\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use LoPati\MenuBundle\Util\Util;
use Gedmo\Mapping\Annotation as Gedmo;




/**
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
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	  */
	protected $links;
	
	/** @ORM\Column(type="boolean", nullable=true) */
	protected $actiu = FALSE;
	
	/** @ORM\Column(type="boolean", nullable=true) */
	protected $portada = FALSE;

	/** @ORM\Column(type="boolean", nullable=true) */
	protected $compartir = FALSE;

	/** @ORM\Column(type="date") */
	protected $data_publicacio;

	/** @ORM\Column(type="boolean", nullable=true) */
	protected $data_visible = FALSE;

	/** @ORM\Column(type="date", nullable=true) */
	protected $data_caducitat = null;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    protected $startDate;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    protected $endDate;

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
	/** 
	 * @ORM\ManyToOne(targetEntity="LoPati\MenuBundle\Entity\Categoria") 
	 */
	protected $categoria;

	/** 
	 * @ORM\ManyToOne(targetEntity="LoPati\MenuBundle\Entity\SubCategoria")	
	 * @ORM\JoinColumn(name="subCategoria_id", referencedColumnName="id", nullable=true) 
	 */
	protected $subCategoria;
	
	/**
	 * @ORM\Column(type="string", length=255, nullable=true )
	 */
	protected $video = NULL;
	
	/**
	 * @ORM\Column(type="string", length=255, nullable=true )
	 */
	protected $urlVimeo = NULL;
	
	/**
	 * @ORM\Column(type="string", length=255, nullable=true )
	 */
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
	
	public function getVideo() {
		return $this->video;
	}
	
	public function setVideo($filename) {
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
		$this->document1=$filename;
	}
	
	public function getDocument1Name() {
		return $this->document1Name;
	}
	
	public function setDocument1Name($file) {
		$this->document1Name=$file;
	}
	
	public function getTitolDocument1() {
		return $this->titolDocument1;
	}
	
	public function setTitolDocument1($file) {
		$this->titolDocument1=$file;
	}

	////////////////////////////////
	
	public function getLinks() {
		return $this->links;
	}
	
	public function setLinks($file) {
		$this->links=$file;
	}
	
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
	
	
	public function getDocument2() {
		return $this->document2;
	}
	
	public function setDocument2($filename) {
		$this->document2=$filename;
	}
	
	public function getDocument2Name() {
		return $this->document2Name;
	}
	
	public function setDocument2Name($file) {
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
		$this->imageGran1Name=$filename;
	}
	
	public function getImageGran1() {
		return $this->imageGran1;
	}
	
	public function setImageGran1($file) {
		$this->imageGran1=$file;
	}
	
	public function getImagePetitaName() {
		return $this->imagePetitaName;
	}
	
	public function setImagePetitaName($filename) {
		$this->imagePetitaName=$filename;
	}
	
	public function getImagePetita() {
		return $this->imagePetita;
	}

	public function setImagePetita($file) {
		$this->imagePetita=$file;
	}
	
	
	public function getImagePetita2() {
		return $this->imagePetita2;
	}
	
	public function setImagePetita2($file) {
		$this->imagePetita2=$file;
	}
	
	public function getImagePetita2Name() {
		return $this->imagePetita2Name;
	}
	
	public function setImagePetita2Name($filename) {
		$this->imagePetita2Name=$filename;
	}
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
	 * @param text $resum
	 */
	public function setResum($resum) {
		$this->resum = $resum;
	}

	/**
	 * Get resum
	 *
	 * @return text 
	 */
	public function getResum() {
		return $this->resum;
	}

	public function __toString() {
		return $this->getTitol();
	}

	/**
	 * Set categoria
	 *
	 * @param LoPati\MenuBundle\Entity\Categoria $categoria
	 */
	public function setCategoria(
			\LoPati\MenuBundle\Entity\Categoria $categoria=null	) {
		$this->categoria = $categoria;
	}

	/**
	 * Get categoria
	 *
	 * @return LoPati\MenuBundle\Entity\Categoria 
	 */
	public function getCategoria() {
		return $this->categoria;
	}

	/**
	 * Set subCategoria
	 *
	 * @param LoPati\MenuBundle\Entity\SubCategoria $SubCategoria
	 */
	public function setSubCategoria(
			\LoPati\MenuBundle\Entity\SubCategoria $subCategoria=null) {
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
	 * @param text $descripcio
	 */
	public function setDescripcio($descripcio) {
		$this->descripcio = $descripcio;
	}

	/**
	 * Get descripcio
	 *
	 * @return text 
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
	 * @param date $dataPublicacio
	 */
	public function setDataPublicacio($dataPublicacio) {
		$this->data_publicacio = $dataPublicacio;
	}

	/**
	 * Get data_publicacio
	 *
	 * @return date 
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
	 * @param date $dataCaducitat
	 */
	public function setDataCaducitat($dataCaducitat) {
		$this->data_caducitat = $dataCaducitat;
	}

	/**
	 * Get data_caducitat
	 *
	 * @return date 
	 */
	public function getDataCaducitat() {
		return $this->data_caducitat;
	}




    /**
	 * Set data_realitzacio
	 *
	 * @param string $dataRealitzacio
	 */
	public function setDataRealitzacio($dataRealitzacio) {
		$this->data_realitzacio = $dataRealitzacio;
	}

	/**
	 * Get data_realitzacio
	 *
	 * @return string 
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
	 * @param date $dataCaducita
	 */
	public function setDataCaducita($dataCaducita) {
		$this->data_caducita = $dataCaducita;
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
     * @return Training
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
}