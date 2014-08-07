<?php

namespace Event\Doctrine\Orm;

use Event\Model\ComputePricesAware;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * An event is an meal/barbecue on a specific date with all its consumptions.
 * User that just take part, can be added by mapping an empty consumptions.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class Event implements ComputePricesAware
{
    /**
     * The primary key for the persistence.
     *
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * An optional name for the event. Using a concatenation
     * of a constant and the date as default.
     *
     * @var string
     */
    private $name;

    /**
     * @var Consumption[]|ArrayCollection
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
        return $this->name;
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
}
