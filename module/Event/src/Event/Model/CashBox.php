<?php

namespace Event\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Event\Doctrine\Orm\Donation;
use Event\Doctrine\Orm\Event;

/**
 * Modeling the CashBox is done by this class.
 *
 * Every CashBox contains a collection of all Events, so it can
 * evaluate the state by checking each of them. To get some money
 * at the beginning, or solve empty states the CashBox got the possibility
 * to add donations.
 *
 * The CashBox isn't persisted. It's just an virtual sum of all Events and Donations.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
class CashBox implements ComputePricesAware
{
    /**
     * @var Event[]
     */
    private $events;

    /**
     * @var Donation[]|ArrayCollection
     */
    private $donations;

    /**
     * To instantiate the collections
     */
    public function __construct()
    {
        $this->donations = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    /**
     * @param Donation[]|ArrayCollection $donations
     */
    public function setDonations($donations)
    {
        $this->donations = $donations;
    }

    /**
     * @return Donation[]|ArrayCollection
     */
    public function getDonations()
    {
        return $this->donations;
    }

    /**
     * @param Event[]|ArrayCollection $events
     */
    public function setEvents($events)
    {
        $this->events = $events;
    }

    /**
     * @return Event[]|ArrayCollection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * {@inheritDoc}
     */
    public function getPriceInComplete()
    {
        $prices = 0;
        /** @var Event[] $events */
        $events = $this->getEvents()->toArray();
        foreach ($events as $event) {
            $prices += $event->getReceivables();
        }

        /** @var Donation[] $donations */
        $donations = $this->getDonations()->toArray();
        foreach ($donations as $donation) {
            $prices -= $donation->getValue();
        }

        return $prices;
    }

    /**
     * {@inheritDoc}
     */
    public function isBalanced()
    {
        return $this->getPriceInComplete() <= 0;
    }

    /**
     * {@inheritDoc}
     */
    public function getReceivables()
    {
        /** @var Event[] $events */
        $events = $this->getEvents()->toArray();
        $receivables = 0;
        foreach ($events as $event) {
            if ($event->isBalanced()) {
                continue;
            }

            $receivables += $event->getReceivables();
        }

        return $receivables;
    }

    /**
     * Creates the sum of all done donations.
     *
     * @return int
     */
    public function getDonationsInComplete()
    {
        $value = 0;
        /** @var Donation[] $donations */
        $donations = $this->getDonations()->toArray();
        foreach ($donations as $donation) {
            $value += $donation->getValue();
        }

        return $value;
    }

    /**
     * Calculates the current value including the donations.
     *
     * @return int
     */
    public function getCurrentValue()
    {
        return $this->getDonationsInComplete()-$this->getReceivables();
    }
}
