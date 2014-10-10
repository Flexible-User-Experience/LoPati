<?php

namespace LoPati\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Arxiu
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="decimal", precision=4, unique=true)
     */
    protected $any = null;

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $actiu = false;

    /**
     * @Assert\File(
     *     maxSize="2M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="imatge", fileNameProperty="imagePetitaName")
     *
     * @var File $imagePetita
     */
    protected $imagePetita;

    /**
     * @ORM\Column(type="string", length=255, name="image_petita_name", nullable=true)
     * @var string $imagePetitaName
     */
    protected $imagePetitaName;

    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="imatge", fileNameProperty="imagePetita2Name")
     *
     * @var File $imagePetita2
     */
    protected $imagePetita2;

    /**
     * @ORM\Column(type="string", length=255, name="image_petita2_name", nullable=true)
     * @var string $imagePetita2Name
     */
    protected $imagePetita2Name;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function getImagePetitaName()
    {
        return $this->imagePetitaName;
    }

    public function setImagePetitaName($filename)
    {
        $this->imagePetitaName = $filename;
    }

    public function getImagePetita()
    {
        return $this->imagePetita;
    }

    public function setImagePetita($file)
    {
        $this->imagePetita = $file;
    }

    public function getImagePetita2()
    {
        return $this->imagePetita2;
    }

    public function setImagePetita2($file)
    {
        $this->imagePetita2 = $file;
    }

    public function getImagePetita2Name()
    {
        return $this->imagePetita2Name;
    }

    public function setImagePetita2Name($filename)
    {
        $this->imagePetita2Name = $filename;
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

    /**
     * Set actiu
     *
     * @param boolean $actiu
     */
    public function setActiu($actiu)
    {
        $this->actiu = $actiu;
    }

    public function __toString()
    {
        return $this->id ? $this->getAny() : '---';
    }

    /**
     * Get data_caducitat
     *
     * @return \DateTime
     */
    public function getAny()
    {
        return $this->any;
    }

    public function setAny($dataCaducitat)
    {
        $this->any = $dataCaducitat;
    }
}
