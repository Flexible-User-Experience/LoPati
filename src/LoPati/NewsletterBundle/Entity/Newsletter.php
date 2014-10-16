<?php

namespace LoPati\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use LoPati\BlogBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use LoPati\BlogBundle\Entity\Pagina;

/**
 * @ORM\Table(name="newsletters")
 * @ORM\Entity(repositoryClass="LoPati\NewsletterBundle\Repository\NewsletterRepository")
 * @UniqueEntity("numero")
 * @UniqueEntity("dataNewsletter")
 */
class Newsletter
{
    /**
     * @ORM\ManyToMany(targetEntity="LoPati\BlogBundle\Entity\Pagina")
     * @ORM\OrderBy({"id"="DESC"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $pagines;

    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var integer $numero
     * @ORM\Column(name="numero", type="integer", unique=true)
     */
    private $numero;

    /**
     * @var string $dataNewsletter
     * @ORM\Column(name="dataNewsletter", type="date")
     */
    private $dataNewsletter;

    /**
     * @var string $iniciEnviament
     * @ORM\Column(name="iniciEnviament", type="datetime", nullable=true)
     */
    private $iniciEnviament = null;

    /**
     * @var string $fiEnviament
     * @ORM\Column(name="fiEnviament", type="datetime", nullable=true)
     */
    private $fiEnviament = null;

    /**
     * @ORM\Column(name="estat", type="string", length=15, nullable=true)
     */
    private $estat = null;

    /**
     * @ORM\Column(name="subscrits", type="integer", nullable=true)
     */
    private $subscrits;

    /**
     * @ORM\Column(name="enviats", type="integer", nullable=true)
     */
    private $enviats;

    /**
     * @var string $sended
     * @ORM\Column(name="test", type="boolean", nullable=true)
     */
    private $test = false;

    /**
     * @ORM\ManyToOne(targetEntity="LoPati\NewsletterBundle\Entity\NewsletterGroup", inversedBy="newsletters")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=true)
     */
    protected $group;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sended = null;
        $this->pagines = new ArrayCollection();
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

    public function setId($created)
    {
        $this->id = $created;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($created)
    {
        $this->numero = $created;
    }

    public function getDataNewsletter()
    {
        return $this->dataNewsletter;
    }

    public function setDataNewsletter($created)
    {
        $this->dataNewsletter = $created;
    }

    public function getIniciEnviament()
    {
        return $this->iniciEnviament;
    }

    public function setIniciEnviament($iniciEnviament)
    {
        $this->iniciEnviament = $iniciEnviament;
    }

    public function getFiEnviament()
    {
        return $this->fiEnviament;
    }

    public function setFiEnviament($fiEnviament)
    {
        $this->fiEnviament = $fiEnviament;
    }

    public function getEstat()
    {
        return $this->estat;
    }

    public function setEstat($estat)
    {
        $this->estat = $estat;
    }

    public function getSubscrits()
    {
        return $this->subscrits;
    }

    public function setSubscrits($estat)
    {
        $this->subscrits = $estat;
    }

    public function getEnviats()
    {
        return $this->enviats;
    }

    public function setEnviats($estat)
    {
        $this->enviats = $estat;
    }

    /**
     * Get category
     *
     * @return ArrayCollection
     */
    public function getPagines()
    {
        return $this->pagines;
    }

    public function setPagines($pagines)
    {
        $this->pagines = $pagines;
    }

    public function addPagines(Pagina $pagina)
    {
        $this->pagines[] = $pagina;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getTest()
    {
        return $this->test;
    }

    public function setTest($created)
    {
        $this->test = $created;
    }

    /**
     * Set Group
     *
     * @param mixed $group group
     *
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get Group
     *
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    public function __toString()
    {
        return $this->id ? (string)$this->getNumero() : '---';
    }
}
