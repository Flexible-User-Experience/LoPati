<?php

namespace LoPati\NewsletterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="newsletter_groups")
 * @ORM\Entity
 * @UniqueEntity("name")
 */
class NewsletterGroup
{
    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     * @ORM\Column(name="name", type="string", length=255, unique=true, nullable=false)
     */
    private $name;

    /**
     * @var boolean $active
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToMany(targetEntity="LoPati\NewsletterBundle\Entity\NewsletterUser", inversedBy="groups")
     * @ORM\OrderBy({"id"="DESC"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * Set Users
     *
     * @param mixed $users users
     *
     * @return $this
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get Users
     *
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add user
     *
     * @param NewsletterUser $user
     *
     * @return $this
     */
    public function addUser(NewsletterUser $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param NewsletterUser $user
     *
     * @return $this
     */
    public function removeUser(NewsletterUser $user)
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name ? $this->name : '---';
    }
}
