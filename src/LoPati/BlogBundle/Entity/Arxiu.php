<?php

namespace LoPati\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Arxiu.
 *
 * @ORM\Table(name="Arxiu")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Arxiu
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=true)
     */
    protected $any;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $actiu = false;

    /**
     * @var File
     *
     * @Assert\File(
     *     maxSize="5M",
     *     mimeTypes={"image/png", "image/jpg", "image/jpeg", "image/pjpeg", "image/gif"}
     * )
     * @Vich\UploadableField(mapping="imatge", fileNameProperty="imagePetitaName")
     */
    protected $imagePetita;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, name="image_petita_name", nullable=true)
     */
    protected $imagePetitaName;

    /**
     * @var File
     *
     * @Assert\File(
     *     maxSize="5M",
     *     mimeTypes={"image/png", "image/jpg", "image/jpeg", "image/pjpeg", "image/gif"}
     * )
     * @Vich\UploadableField(mapping="imatge", fileNameProperty="imagePetita2Name")
     */
    protected $imagePetita2;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, name="image_petita2_name", nullable=true)
     */
    protected $imagePetita2Name;

    /**
     * Methods.
     */

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getAny()
    {
        return $this->any;
    }

    /**
     * @param $any
     *
     * @return $this
     */
    public function setAny($any)
    {
        $this->any = $any;

        return $this;
    }

    /**
     * @return bool
     */
    public function getActiu()
    {
        return $this->actiu;
    }

    /**
     * @param bool $actiu
     *
     * @return $this
     */
    public function setActiu($actiu)
    {
        $this->actiu = $actiu;

        return $this;
    }

    /**
     * @return File
     */
    public function getImagePetita()
    {
        return $this->imagePetita;
    }

    /**
     * @param File $file
     *
     * @return $this
     */
    public function setImagePetita($file)
    {
        $this->imagePetita = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getImagePetitaName()
    {
        return $this->imagePetitaName;
    }

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function setImagePetitaName($filename)
    {
        $this->imagePetitaName = $filename;

        return $this;
    }

    /**
     * @return File
     */
    public function getImagePetita2()
    {
        return $this->imagePetita2;
    }

    /**
     * @param File $file
     *
     * @return $this
     */
    public function setImagePetita2($file)
    {
        $this->imagePetita2 = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getImagePetita2Name()
    {
        return $this->imagePetita2Name;
    }

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function setImagePetita2Name($filename)
    {
        $this->imagePetita2Name = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? (string) $this->getAny() : '---';
    }
}
