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
     * @var int
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
     * @Assert\Email(checkMX=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var int
     *
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $postalCode;

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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $birthyear;

    /**
     * @var int
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="idioma", type="string", length=2, options={"default"="ca"})
     */
    private $idioma = 'ca';

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
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", options={"default"=1})
     */
    private $active = true;

    /**
     * @var int
     *
     * @ORM\Column(name="fail", type="integer", nullable=true)
     */
    private $fail = 0;

    /**
     * @var string
     */
    private $improtedGroup;

    /**
     * @var array|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="LoPati\NewsletterBundle\Entity\NewsletterGroup", mappedBy="users")
     */
    protected $groups;

    /**
     * Methods.
     */

    /**
     * NewsletterUser constructor.
     */
    public function __construct()
    {
        $this->token = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->groups = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $idioma
     *
     * @return $this
     */
    public function setIdioma($idioma)
    {
        $this->idioma = $idioma;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param bool $active
     *
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param \DateTime $created
     *
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getCreatedString()
    {
        return $this->created->format('d/m/Y H:i:s');
    }

    /**
     * @param int $fail
     *
     * @return $this
     */
    public function setFail($fail)
    {
        $this->fail = $fail;

        return $this;
    }

    /**
     * @return int
     */
    public function getFail()
    {
        return $this->fail;
    }

    /**
     * @param array $groups
     *
     * @return $this
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @return array|ArrayCollection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return string
     */
    public function getGroupsString()
    {
        if (count($this->groups) > 0) {
            $result = '';
            /** @var NewsletterGroup $group */
            foreach ($this->groups as $group) {
                $result .= $group->getName().', ';
            }

            return substr($result, 0, -2);
        }

        return '';
    }

    /**
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
     * @return $this
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
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return int
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param int $postalCode
     *
     * @return $this
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

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
     * @return $this
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
     * @return $this
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int $age
     *
     * @return $this
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return int
     */
    public function getAgeTransformed()
    {
        $result = 0;
        if ($this->birthyear) {
            $currentYear = new \DateTime();
            $result = intval($currentYear->format('Y')) - $this->getBirthyear();
        }

        return $result;
    }

    /**
     * @param int $age
     *
     * @return $this
     */
    public function setAgeTransformed($age)
    {
        if ($age) {
            $currentYear = new \DateTime();
            $this->birthyear = intval($currentYear->format('Y')) - $age;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getBirthyear()
    {
        return $this->birthyear;
    }

    /**
     * @param int $birthyear
     *
     * @return $this
     */
    public function setBirthyear($birthyear)
    {
        $this->birthyear = $birthyear;

        return $this;
    }

    /**
     * @return string
     */
    public function getImprotedGroup()
    {
        return $this->improtedGroup;
    }

    /**
     * @param string $improtedGroup
     *
     * @return $this
     */
    public function setImprotedGroup($improtedGroup)
    {
        $this->improtedGroup = $improtedGroup;

        return $this;
    }

    /**
     * @return string
     */
    public function getImportXlsString()
    {
        return 'NOM='.$this->name.' · EMAIL='.$this->email.' · GRUP='.$this->improtedGroup.' · CIUTAT='.$this->city.' · EDAT='.$this->getAge().' · TEL='.$this->phone;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->email ? $this->email : '---';
    }
}
