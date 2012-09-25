<?php

namespace LoPati\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * LoPati\NewsletterBundle\Entity\NewsletterSend
 *
 * @ORM\Table(name="newsletterSend")
 * @ORM\Entity
 * @UniqueEntity({"user","newsletter"})
 */

class NewsletterSend
{
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * 
	 */
	private $id;
	
	
	/** @ORM\ManyToOne(targetEntity="NewsletterUSer")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $user;
	
	/** @ORM\ManyToOne(targetEntity="Newsletter")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $newsletter;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getUser()
	{
		return $this->user;
	}
	public function setUser($user)
	{
		$this->user = $user;
	}
	
	public function getNewsletter()
	{
		return $this->newsletter;
	}
	
	public function setNewsletter($newsletter)
	{
		$this->newsletter = $newsletter;
	}
}