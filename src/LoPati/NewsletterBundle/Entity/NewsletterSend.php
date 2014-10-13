<?php

namespace LoPati\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * LoPati\NewsletterBundle\Entity\NewsletterSend
 *
 * @ORM\Table(name="newslettersend")
 * @ORM\Entity
 * @UniqueEntity({"user","newsletter"})
 */
class NewsletterSend
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
     * @var NewsletterUser
     *
     * @ORM\ManyToOne(targetEntity="NewsletterUser")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $user;
	
	/**
     * @var Newsletter
     *
     * @ORM\ManyToOne(targetEntity="Newsletter")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $newsletter;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
	{
		return $this->id;
	}

    /**
     * Get user
     *
     * @return NewsletterUser
     */
    public function getUser()
	{
		return $this->user;
	}

    /**
     * Set user
     *
     * @param $user
     *
     * @return $this
     */
    public function setUser($user)
	{
		$this->user = $user;

        return $this;
	}

    /**
     * Get newsletter
     *
     * @return Newsletter
     */
    public function getNewsletter()
	{
		return $this->newsletter;
	}

    /**
     * Set newsletter
     *
     * @param $newsletter
     *
     * @return $this
     */
    public function setNewsletter($newsletter)
	{
		$this->newsletter = $newsletter;

        return $this;
	}
}
