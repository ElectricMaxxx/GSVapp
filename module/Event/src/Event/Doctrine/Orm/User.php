<?php


namespace Event\Doctrine\Orm;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Event\Model\ExchangeArrayInterface;

/**
 * Describes an user as an identity to take part an event and/or consume some meals.
 * @ORM\Entity
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class User implements ExchangeArrayInterface
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
     * Unique name of the user.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $username;

    /**
     * @var string
     *
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @var Donation[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Event\Doctrine\Orm\Donation", mappedBy="user", cascade={"persist"})
     */
    private $donations;

    public function __construct()
    {
        $eventConsumptions = array();
        $this->donations = new ArrayCollection();
    }

    /**
     * @param ArrayCollection|Donation[] $donations
     */
    public function setDonations($donations)
    {
        $this->donations = $donations;
    }

    /**
     * @return ArrayCollection|Donation[]
     */
    public function getDonations()
    {
        return $this->donations;
    }

    /**
     * @param Donation $donation
     */
    public function addDonation(Donation $donation)
    {
        $donation->setUser($this);
        $this->donations->add($donation);
    }

    /**
     * @param Donation $donation
     */
    public function removeDonation(Donation $donation)
    {
        $this->donations->remove($donation);
    }

    /**
     * Every user got a number of, so he took part on every of that events.
     *
     * @var EventConsumption[]|ArrayCollection
     */
    private $eventConsumptions;

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
     * @param ArrayCollection|\Event\Doctrine\Orm\EventConsumption[] $eventConsumptions
     */
    public function setEventConsumptions($eventConsumptions)
    {
        $this->eventConsumptions = $eventConsumptions;
    }

    /**
     * @return ArrayCollection|\Event\Doctrine\Orm\EventConsumption[]
     */
    public function getEventConsumptions()
    {
        return $this->eventConsumptions;
    }

    /**
     * {@inheritDoc}
     */
    public function exchangeArray(array $data)
    {
        foreach ($data as $key => $value) {
            if (method_exists($this, 'set'.ucfirst($key))) {
                $this->{'set'.ucfirst($key)}($value);
            }
        }
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
        return $this->getUsername();
    }
}
