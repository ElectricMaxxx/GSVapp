<?php


namespace Event\Doctrine\Orm;

use Event\Model\ComputePricesAware;
use Event\Exception\InvalidArgumentException;

/**
 * A consumption describes a specific part of a meal with an amount
 * of a meal.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class Consumption implements ComputePricesAware
{
    /**
     * Defining some constants for sane states
     */
    const STATE_PRE_ORDERED = 'ordered';
    const STATE_POST_SET = 'post_set';
    const STATE_PAID = 'paid';

    /**
     * The primary key for the persistence.
     *
     * @var int
     */
    private $id;

    /**
     * @var Meal
     */
    private $meal;

    /**
     * The amount of the mails for this consumption.
     *
     * @var int
     */
    private $amountOf;

    /**
     * Need to be one of the constants defined above.
     *
     * @var string
     */
    private $currentState;

    /**
     * @var Event
     */
    private $event;

    /**
     * @var User
     */
    private $user;

    /**
     * @param Meal $meal
     * @param int $amountOf
     */
    public function __construct(Meal $meal = null, $amountOf = 1)
    {
        $this->meal = $meal;
        $this->amountOf = $amountOf;
    }

    /**
     * @param int $amountOf
     */
    public function setAmountOf($amountOf)
    {
        $this->amountOf = $amountOf;
    }

    /**
     * @return int
     */
    public function getAmountOf()
    {
        return $this->amountOf;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Meal $meal
     */
    public function setMeal(Meal $meal)
    {
        $this->meal = $meal;
    }

    /**
     * @return Meal
     */
    public function getMeal()
    {
        return $this->meal;
    }

    /**
     * @param string $currentState
     * @throws InvalidArgumentException
     */
    public function setCurrentState($currentState)
    {
        $states = array(self::STATE_PAID, self::STATE_POST_SET, self::STATE_PRE_ORDERED);
        if (!in_array($currentState, $states)) {
            throw new InvalidArgumentException(
                sprintf(
                    'State %s is not allowed to set, use one of  %s instead',
                    $currentState,
                    implode(', ', $states)
                )
            );
        }

        $this->currentState = $currentState;
    }

    /**
     * @return string
     */
    public function getCurrentState()
    {
        return $this->currentState;
    }

    /**
     * @param Event $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
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
     * {@inheritDoc}
     */
    public function getPriceInComplete()
    {
        return $this->amountOf*$this->meal->getPrice();
    }

    /**
     * {@inheritDoc}
     */
    public function isBalanced()
    {
        return $this->currentState === self::STATE_PAID;
    }

    /**
     * {@inheritDoc}
     *
     * @return int
     */
    public function getReceivables()
    {
        if ($this->isBalanced()) {
            return 0;
        }

        return $this->getPriceInComplete();
    }
}
