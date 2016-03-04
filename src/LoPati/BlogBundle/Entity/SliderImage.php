<?php

namespace LoPati\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="slider_image")
 * @ORM\Entity(repositoryClass="LoPati\BlogBundle\Repository\SliderImageRepository")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class SliderImage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    protected $altName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url()
     * @var string
     */
    protected $link;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $position = 0;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @var bool
     */
    protected $active = true;

    /**
     * @Assert\File(
     *     maxSize="5M",
     *     mimeTypes={"image/png", "image/jpg", "image/jpeg", "image/pjpeg", "image/gif"}
     * )
     * @Vich\UploadableField(mapping="slider", fileNameProperty="image")
     * @var File $imageFile
     */
    protected $imageFile;

    /**
     * @ORM\Column(type="string", length=255, name="image", nullable=false)
     * @var string $image
     */
    protected $image;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;

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
     * Set Active
     *
     * @param boolean $active active
     *
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get Active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set AltName
     *
     * @param string $altName altName
     *
     * @return $this
     */
    public function setAltName($altName)
    {
        $this->altName = $altName;

        return $this;
    }

    /**
     * Get AltName
     *
     * @return string
     */
    public function getAltName()
    {
        return $this->altName;
    }

    /**
     * Set Description
     *
     * @param string $description description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set Image
     *
     * @param string $image image
     *
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;
        $this->updated = new \DateTime();

        return $this;
    }

    /**
     * Get Image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set ImageFile
     *
     * @param \Symfony\Component\HttpFoundation\File\File $imageFile imageFile
     *
     * @return $this
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
        $this->updated = new \DateTime();

        return $this;
    }

    /**
     * Get ImageFile
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set Link
     *
     * @param string $link link
     *
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get Link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set Name
     *
     * @param string $name name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Position
     *
     * @param mixed $position position
     *
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get Position
     *
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set Created
     *
     * @param mixed $created created
     *
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get Created
     *
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set Updated
     *
     * @param mixed $updated updated
     *
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get Updated
     *
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    public function __toString()
    {
        return $this->id ? $this->name : '---';
    }
}
