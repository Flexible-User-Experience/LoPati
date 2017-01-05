<?php

namespace LoPati\NewsletterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="newsletter_users")
 * @ORM\Entity(repositoryClass="LoPati\NewsletterBundle\Repository\NewsletterUserRepository")
 * @UniqueEntity("email")
 */
class NewsletterUser
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
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthdate;

    /**
     * @var string
     *
     * @ORM\Column(name="idioma", type="string", length=2)
     */
    private $idioma;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var int
     *
     * @ORM\Column(name="fail", type="integer")
     */
    private $fail = 0;

    /**
     * @var array|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="LoPati\NewsletterBundle\Entity\NewsletterGroup", mappedBy="users")
     */
    protected $groups;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * NewsletterUser constructor
     */
    public function __construct()
    {
        $this->token = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->active = false;
        $this->groups = new ArrayCollection();
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
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    public function setIdioma($idioma)
    {
        $this->idioma = $idioma;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * Set token
     *
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set active
     *
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Get created string format
     *
     * @return string
     */
    public function getCreatedString()
    {
        return $this->created->format('d/m/Y H:i:s');
    }

    public function setFail($fail)
    {
        $this->fail = $fail;
    }

    /**
     * Get fail
     *
     * @return \DateTime
     */
    public function getFail()
    {
        return $this->fail;
    }

    /**
     * Set Groups
     *
     * @param mixed $groups groups
     *
     * @return $this
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get Groups
     *
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add group
     *
     * @param NewsletterGroup $group
     *
     * @return $this
     */
    public function addGroup(NewsletterGroup $group)
    {
        $this->groups[] = $group;
        $group->addUser($this);

        return $this;
    }

    /**
     * Remove group
     *
     * @param NewsletterGroup $group
     *
     * @return $this
     */
    public function removeGroup(NewsletterGroup $group)
    {
        $group->removeUser($this);
        $this->groups->removeElement($group);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return NewsletterUser
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return NewsletterUser
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return NewsletterUser
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param \DateTime $birthdate
     *
     * @return NewsletterUser
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->email ? $this->email : '---';
    }
}