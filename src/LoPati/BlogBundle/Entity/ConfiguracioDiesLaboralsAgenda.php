<?php
namespace LoPati\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use LoPati\MenuBundle\Util\Util;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
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

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $workingDay2;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $workingDay3;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $workingDay4;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $workingDay5;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $workingDay6;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $workingDay7;

    /**
     * Get id
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function getWorkingDay1() {
        return $this->workingDay1;
    }

    public function getWorkingDay2() {
        return $this->workingDay2;
    }

    public function getWorkingDay3() {
        return $this->workingDay3;
    }

    public function getWorkingDay4() {
        return $this->workingDay4;
    }

    public function getWorkingDay5() {
        return $this->workingDay5;
    }

    public function getWorkingDay6() {
        return $this->workingDay6;
    }

    public function getWorkingDay7() {
        return $this->workingDay7;
    }

    public function setWorkingDay1($day) {
        $this->workingDay1 = $day;
        return $this;
    }

    public function setWorkingDay2($day) {
        $this->workingDay2 = $day;
        return $this;
    }

    public function setWorkingDay3($day) {
        $this->workingDay3 = $day;
        return $this;
    }

    public function setWorkingDay4($day) {
        $this->workingDay4 = $day;
        return $this;
    }

    public function setWorkingDay5($day) {
        $this->workingDay5 = $day;
        return $this;
    }

    public function setWorkingDay6($day) {
        $this->workingDay6 = $day;
        return $this;
    }

    public function setWorkingDay7($day) {
        $this->workingDay7 = $day;
        return $this;
    }

}