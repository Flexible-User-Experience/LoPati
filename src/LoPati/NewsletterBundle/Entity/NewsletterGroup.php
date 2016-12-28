<?php

namespace LoPati\NewsletterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @category Entity
 * @package  LoPati\NewsletterBundle\Entity
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Table(name="newsletter_groups")
 * @ORM\Entity(repositoryClass="LoPati\NewsletterBundle\Repository\NewsletterGroupRepository")
 * @UniqueEntity("name")
 */
class NewsletterGroup
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true, nullable=false)
     */
    private $name;

    /**
     * @var boolean $active
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="LoPati\NewsletterBundle\Entity\NewsletterUser", inversedBy="groups")
     * @ORM\OrderBy({"email"="ASC"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $users;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="LoPati\NewsletterBundle\Entity\NewsletterPage", mappedBy="group")
     */
    protected $newsletters;

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
        $this->users = new ArrayCollection();
        $this->newsletters = new ArrayCollection();
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
     * @param string|NewsletterUser $user
     *
     * @return $this
     */
    public function addUser($user)
    {
        // TODO: Bad practice! Avoid this with datatransformer (not working with sonata admin)
        if (!$user instanceof NewsletterUser) {
            global $kernel;
            if ('AppCache' == get_class($kernel)) {
                $kernel = $kernel->getKernel();
            }
            $user = $kernel->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NewsletterBundle:NewsletterUser')->find($user);
        }
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
     * Set Newsletters
     *
     * @param mixed $newsletters newsletters
     *
     * @return $this
     */
    public function setNewsletters($newsletters)
    {
        $this->newsletters = $newsletters;

        return $this;
    }

    /**
     * Get Newsletters
     *
     * @return mixed
     */
    public function getNewsletters()
    {
        return $this->newsletters;
    }

    /**
     * Add newsletter
     *
     * @param NewsletterPage $newsletter
     *
     * @return $this
     */
    public function addNewsletter(NewsletterPage $newsletter)
    {
        $this->newsletters[] = $newsletter;

        return $this;
    }

    /**
     * Remove newsletter
     *
     * @param NewsletterPage $newsletter
     *
     * @return $this
     */
    public function removeNewsletter(NewsletterPage $newsletter)
    {
        $this->newsletters->removeElement($newsletter);

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
