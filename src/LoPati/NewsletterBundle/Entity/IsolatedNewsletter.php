<?php

namespace LoPati\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class IsolatedNewsletter
 *
 * @category Entity
 * @package  LoPati\NewsletterBundle\Entity
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Table(name="isolated_newsletter")
 * @ORM\Entity(repositoryClass="LoPati\NewsletterBundle\Repository\IsolatedNewsletterRepository")
 */
class IsolatedNewsletter
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $subject;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false, options={"default"=0})
     */
    private $state = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default"=0})
     */
    private $tested = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $beginSend = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endSend = null;

    /**
     * @var array|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="IsolatedNewsletterPost", mappedBy="newsletter")
     */
    private $posts;

    /**
     * @var NewsletterGroup
     *
     * @ORM\ManyToOne(targetEntity="NewsletterGroup")
     * @ORM\JoinColumn(name="newsletter_group_id", referencedColumnName="id", nullable=true)
     */
    private $group;

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
        $this->posts = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     *
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param int $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTested()
    {
        return $this->tested;
    }

    /**
     * @return bool
     */
    public function getTested()
    {
        return $this->tested;
    }

    /**
     * @param bool $tested
     *
     * @return $this
     */
    public function setTested($tested)
    {
        $this->tested = $tested;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBeginSend()
    {
        return $this->beginSend;
    }

    /**
     * @param \DateTime $beginSend
     *
     * @return $this
     */
    public function setBeginSend($beginSend)
    {
        $this->beginSend = $beginSend;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndSend()
    {
        return $this->endSend;
    }

    /**
     * @param \DateTime $endSend
     *
     * @return $this
     */
    public function setEndSend($endSend)
    {
        $this->endSend = $endSend;

        return $this;
    }

    /**
     * @return array|ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param array|ArrayCollection $posts
     *
     * @return $this
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * @param IsolatedNewsletterPost $post
     *
     * @return $this
     */
    public function addPost($post)
    {
        $this->posts->add($post);
        $post->setNewsletter($this);

        return $this;
    }

    /**
     * @param IsolatedNewsletterPost $post
     *
     * @return $this
     */
    public function removePost($post)
    {
        $this->posts->removeElement($post);

        return $this;
    }

    /**
     * @return NewsletterGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param NewsletterGroup $group
     *
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getDate()->format('d/m/Y') . ' · ' . $this->subject : '---';
    }
}
