<?php

namespace Event\Doctrine\Orm;

use Doctrine\ORM\Mapping as ORM;
use Event\Model\ExchangeArrayInterface;

/**
 * A donation is a possibility to add some money to the CashBox or
 * solve empty states.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 *
 * @ORM\Entity
 */
class Donation implements ExchangeArrayInterface
{
    /**
     * The primary key for the persistence.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * The value of the donation in euro cents.
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @var User ..., which does the donation, to get the money back - later, maybe.
     *
     * @ORM\ManyToOne(targetEntity="Event\Doctrine\Orm\User", inversedBy="donations", cascade={"persist"})
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


    /**
     * {@inheritDoc}
     */
    public function exchangeArray(array $data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->value = isset($data['value']) ? $data['value'] : null;
        $this->user = isset($data['user']) ? $data['user'] : null;
    }

    /**
     * {@inheritDoc}
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function __toString()
    {
        return 'Gutschrifft '.$this->getUser().'('.round($this->getValue()/100, 2).' €)';
    }
}
