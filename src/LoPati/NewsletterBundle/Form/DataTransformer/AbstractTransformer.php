<?php

namespace LoPati\NewsletterBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

abstract class AbstractTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Set Om
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $om om
     *
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    public function setOm($om)
    {
        $this->om = $om;

        return $this;
    }

    /**
     * Get Om
     *
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    public function getOm()
    {
        return $this->om;
    }

    /**
     * Transforms an object to a string
     *
     * @param object|null $object
     *
     * @return string
     */
    abstract public function transform($object);

    /**
     * Transforms a string to object
     *
     * @param string $number
     *
     * @return object|null
     * @throws TransformationFailedException if object is not found
     */
    abstract public function reverseTransform($number);
}
