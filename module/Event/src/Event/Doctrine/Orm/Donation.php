<?php

namespace Event\Doctrine\Orm;

/**
 * A donation is a possibility to add some money to the CashBox or
 * solve empty states.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
class Donation
{
    /**
     * The primary key for the persistence.
     *
     * @var int
     */
    private $id;

    /**
     * The value of the donation in euro cents.
     *
     * @var int
     */
    private $value;

    /**
     * @var User ..., which does the donation, to get the money back - later, maybe.
     */
    private $user;

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
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param int $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
}
 