<?php


namespace Event\Doctrine\Orm;

use Doctrine\Common\Collections\ArrayCollection;
use Event\Doctrine\Orm\EventConsumption;

/**
 * Describes an user as an identity to take part an event and/or consume some meals.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class User
{
    /**
     * The primary key for the persistence.
     *
     * @var int
     */
    private $id;

    /**
     * Unique name of the user.
     *
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $email;

    /**
     * Every user got a number of, so he took part on every of that events.
     *
     * @var EventConsumption[]|ArrayCollection
     */
    private $eventConsumptions;

    public function __construct()
    {
        $this->consumptions = array();
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|\Event\Doctrine\Orm\EventConsumption[] $eventConsumptions
     */
    public function setEventConsumptions($eventConsumptions)
    {
        $this->eventConsumptions = $eventConsumptions;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Event\Doctrine\Orm\EventConsumption[]
     */
    public function getEventConsumptions()
    {
        return $this->eventConsumptions;
    }
}
