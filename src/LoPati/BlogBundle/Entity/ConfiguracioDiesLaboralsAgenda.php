<?php

namespace LoPati\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="LoPati\BlogBundle\Repository\ConfiguracioDiesLaboralsAgendaRepository")
 */
class ConfiguracioDiesLaboralsAgenda {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $workingDay1;

    /** @ORM\Column(type="string", length=25, nullable=false) */
    protected $name;

    /** @ORM\Column(type="boolean", nullable=false) */
    protected $active = FALSE;

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
     * Set id
     *
     * @param integer $id
     * @return ConfiguracioDiesLaboralsAgenda
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set workingDay1
     *
     * @param integer $workingDay1
     *
     * @return ConfiguracioDiesLaboralsAgenda
     */
    public function setWorkingDay1($workingDay1)
    {
        $this->workingDay1 = $workingDay1;
    
        return $this;
    }

    /**
     * Get workingDay1
     *
     * @return integer 
     */
    public function getWorkingDay1()
    {
        return $this->workingDay1;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ConfiguracioDiesLaboralsAgenda
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return ConfiguracioDiesLaboralsAgenda
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
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
}
