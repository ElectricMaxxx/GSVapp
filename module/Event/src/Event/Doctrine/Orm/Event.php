<?php

namespace Event\Doctrine\Orm;

use Event\Model\ComputePricesAware;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Event\Model\ExchangeArrayInterface;

/**
 * An event is an meal/barbecue on a specific date with all its consumptions.
 * User that just take part, can be added by mapping an empty consumptions.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 *
 * @ORM\Entity
 */
class Event implements ComputePricesAware, ExchangeArrayInterface
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
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * An optional name for the event. Using a concatenation
     * of a constant and the date as default.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var Consumption[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Event\Doctrine\Orm\Consumption",cascade={"persist"})
     * @ORM\JoinTable(name="event_consumptions",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="consumption_id", referencedColumnName="id")}
     *      )
     */
    private $consumptions;

    public function __construct()
    {
        $this->consumptions = new ArrayCollection();
    }

    /**
     * @param ArrayCollection|Consumption[] $consumptions
     */
    public function setConsumptions($consumptions)
    {
        $this->consumptions = $consumptions;
    }

    /**
     * @return ArrayCollection|Consumption[]
     */
    public function getConsumptions()
    {
        return $this->consumptions;
    }

    /**
     * @param Consumption $consumption
     */
    public function addConsumption(Consumption $consumption)
    {
        $this->consumptions->add($consumption);
    }

    /**
     * @param Consumption $consumption
     */
    public function removeConsumption(Consumption $consumption)
    {
        $this->consumptions->removeElement($consumption);
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return null === $this->name && $this->date instanceof \DateTime
            ? 'Grillen am '.$this->date->format('d.m.Y')
            : $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getPriceInComplete()
    {
        $price = 0;
        /** @var Consumption[] $consumptions */
        $consumptions = $this->consumptions->toArray();
        foreach ($consumptions as $consumption) {
            $price += $consumption->getPriceInComplete();
        }

        return $price;
    }

    /**
     * {@inheritDoc}
     */
    public function isBalanced()
    {
        /** @var Consumption[] $consumptions */
        $consumptions = $this->consumptions->toArray();
        foreach ($consumptions as $consumption) {
            if (!$consumption->isBalanced()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return int
     */
    public function getReceivables()
    {
        $receivables = 0;
        /** @var Consumption[] $consumptions */
        $consumptions = $this->consumptions->toArray();
        foreach ($consumptions as $consumption) {
            $receivables += $consumption->getReceivables();
        }

        return $receivables;
    }

    public function __toString()
    {
        return $this->getName();
    }


    /**
     * {@inheritDoc}
     */
    public function exchangeArray(array $data)
    {
        $this->id = (isset($data['id']))     ? $data['id']     : null;
        $this->date = (isset($data['date']))
            ? new \DateTime((string) $data['date'], new \DateTimeZone('Europe/Berlin'))
            : null;
    }

    /**
     * {@inheritDoc}
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
