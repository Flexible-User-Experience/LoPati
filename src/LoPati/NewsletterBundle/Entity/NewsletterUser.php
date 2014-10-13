<?php

namespace LoPati\NewsletterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * LoPati\NewsletterBundle\Entity\NewsletterUser
 * @ORM\Table(name="newsletter_users")
 * @ORM\Entity
 * @UniqueEntity("email")
 */
class NewsletterUser
{
    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $email
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string $idioma
     * @ORM\Column(name="idioma", type="string", length=2)
     */
    private $idioma;

    /**
     * @var string $token
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var string $created
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var boolean $active
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var boolean $active
     * @ORM\Column(name="fail", type="integer")
     */
    private $fail = 0;

    /**
     * @ORM\ManyToMany(targetEntity="LoPati\NewsletterBundle\Entity\NewsletterGroup", mappedBy="users")
     */
    protected $groups;

    public function __construct()
    {
        $this->token = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->active = false;
        $this->created = new \DateTime();
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
        $this->groups->removeElement($group);

        return $this;
    }

    public function __toString()
    {
        return $this->email ? $this->email : '---';
    }
}