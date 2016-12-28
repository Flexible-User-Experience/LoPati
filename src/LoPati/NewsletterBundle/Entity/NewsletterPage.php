<?php

namespace LoPati\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use LoPati\BlogBundle\Entity\Pagina;

/**
 * @category Entity
 * @package  LoPati\NewsletterBundle\Entity
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Table(name="newsletters")
 * @ORM\Entity(repositoryClass="LoPati\NewsletterBundle\Repository\NewsletterPageRepository")
 * @UniqueEntity("numero")
 * @UniqueEntity("dataNewsletter")
 */
class NewsletterPage
{
    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="LoPati\BlogBundle\Entity\Pagina")
     * @ORM\OrderBy({"id"="DESC"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $pagines;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var integer $numero
     *
     * @ORM\Column(name="numero", type="integer", unique=true)
     */
    private $numero;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime $dataNewsletter
     *
     * @ORM\Column(name="dataNewsletter", type="date")
     */
    private $dataNewsletter;

    /**
     * @var \DateTime $iniciEnviament
     *
     * @ORM\Column(name="iniciEnviament", type="datetime", nullable=true)
     */
    private $iniciEnviament = null;

    /**
     * @var \DateTime $fiEnviament
     *
     * @ORM\Column(name="fiEnviament", type="datetime", nullable=true)
     */
    private $fiEnviament = null;

    /**
     * @var string
     *
     * @ORM\Column(name="estat", type="string", length=15, nullable=true)
     */
    private $estat = null;

    /**
     * @var integer
     *
     * @ORM\Column(name="subscrits", type="integer", nullable=true)
     */
    private $subscrits;

    /**
     * @var integer
     *
     * @ORM\Column(name="enviats", type="integer", nullable=true)
     */
    private $enviats;

    /**
     * @var boolean $sended
     *
     * @ORM\Column(name="test", type="boolean", nullable=true)
     */
    private $test = false;

    /**
     * @var array
     *
     * @ORM\ManyToOne(targetEntity="LoPati\NewsletterBundle\Entity\NewsletterGroup", inversedBy="newsletters")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=true)
     */
    protected $group;

    /**
     *
     *
     * Methods
     *
     *
     */

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

    /**
     * @param integer $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param $number
     *
     * @return $this
     */
    public function setNumero($number)
    {
        $this->numero = $number;

        return $this;
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
     * @return \DateTime
     */
    public function getDataNewsletter()
    {
        return $this->dataNewsletter;
    }

    /**
     * @param \DateTime $created
     *
     * @return $this
     */
    public function setDataNewsletter($created)
    {
        $this->dataNewsletter = $created;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getIniciEnviament()
    {
        return $this->iniciEnviament;
    }

    /**
     * @param \DateTime $iniciEnviament
     *
     * @return $this
     */
    public function setIniciEnviament($iniciEnviament)
    {
        $this->iniciEnviament = $iniciEnviament;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFiEnviament()
    {
        return $this->fiEnviament;
    }

    /**
     * @param \DateTime $fiEnviament
     *
     * @return $this
     */
    public function setFiEnviament($fiEnviament)
    {
        $this->fiEnviament = $fiEnviament;

        return $this;
    }

    /**
     * @return string
     */
    public function getEstat()
    {
        return $this->estat;
    }

    /**
     * @param string $estat
     *
     * @return $this
     */
    public function setEstat($estat)
    {
        $this->estat = $estat;

        return $this;
    }

    /**
     * @return int
     */
    public function getSubscrits()
    {
        return $this->subscrits;
    }

    /**
     * @param int $estat
     *
     * @return $this
     */
    public function setSubscrits($estat)
    {
        $this->subscrits = $estat;

        return $this;
    }

    /**
     * @return int
     */
    public function getEnviats()
    {
        return $this->enviats;
    }

    /**
     * @param int $estat
     *
     * @return $this
     */
    public function setEnviats($estat)
    {
        $this->enviats = $estat;

        return $this;
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

    /**
     * @param ArrayCollection $pagines
     *
     * @return $this
     */
    public function setPagines($pagines)
    {
        $this->pagines = $pagines;

        return $this;
    }

    /**
     * @param Pagina $pagina
     */
    public function addPagines(Pagina $pagina)
    {
        $this->pagines[] = $pagina;
    }

    /**
     * Get created
     *
     * @return boolean
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param boolean $test
     *
     * @return $this
     */
    public function setTest($test)
    {
        $this->test = $test;

        return $this;
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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? (string)$this->getNumero() : '---';
    }
}
