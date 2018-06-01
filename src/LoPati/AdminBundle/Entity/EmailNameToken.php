<?php

namespace LoPati\AdminBundle\Entity;

/**
 * Class EmailNameToken.
 */
class EmailNameToken extends EmailToken
{
    /**
     * @var string
     */
    private $name;

    /**
     * Methods.
     */

    /**
     * EmailNameToken constructor.
     *
     * @param string $email
     * @param string $name
     * @param string $token
     */
    public function __construct($email, $name, $token)
    {
        parent::__construct($email, $token);
        $this->name = $name;
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
}
