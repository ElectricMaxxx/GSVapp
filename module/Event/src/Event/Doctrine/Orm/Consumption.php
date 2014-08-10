<?php


namespace Event\Doctrine\Orm;

use Event\Exception\InvalidOperation;
use Event\Model\ComputePricesAware;
use Event\Exception\InvalidArgument;
use Doctrine\ORM\Mapping as ORM;

/**
 * A consumption describes a specific part of a meal with an amount
 * of a meal.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 *
 * @ORM\Entity(repositoryClass="Event\Doctrine\Orm\ConsumptionsRepository")
 */
class Consumption implements ComputePricesAware
{
    /**
     * Defining some constants for sane states
     */
    const STATE_NOT_SET = 0;
    const STATE_PRE_ORDERED = 1;
    const STATE_POST_SET = 2;
    const STATE_PAID = 3;

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
     * @var Meal
     *
     * @ORM\ManyToOne(targetEntity="Event\Doctrine\Orm\Meal")
     */
    private $meal;

    /**
     * The amount of the mails for this consumption.
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $amountOf;

    /**
     * The current value of the meal when booking this consumption.
     *
     * This one will be persisted and used for the calculations.
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $currentPriceOfMeal;

    /**
     * Need to be one of the constants defined above.
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $currentState = 0;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="Event\Doctrine\Orm\Meal")
     */
    private $event;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Event\Doctrine\Orm\User")
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
        $this->currentPriceOfMeal = $meal->getPrice();
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
     * @throws InvalidArgument
     */
    public function setCurrentState($currentState)
    {
        $states = array(self::STATE_PAID, self::STATE_POST_SET, self::STATE_PRE_ORDERED);
        if (!in_array($currentState, $states)) {
            throw new InvalidArgument(
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
        if (null === $this->meal || null === $this->amountOf) {
            throw new InvalidOperation('You should have set a meal and its amount before starting to calculate.');
        }

        // Price will be fixed calculating the the first time, or by setting  meal with a valid value
        if (null === $this->currentPriceOfMeal) {
            $this->currentPriceOfMeal = $this->meal->getPrice();
        }

        return $this->amountOf*$this->currentPriceOfMeal;
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

    /**
     * @param int $currentPriceOfMeal
     */
    public function setCurrentPriceOfMeal($currentPriceOfMeal)
    {
        $this->currentPriceOfMeal = $currentPriceOfMeal;
    }

    /**
     * @return int
     */
    public function getCurrentPriceOfMeal()
    {
        return $this->currentPriceOfMeal;
    }
}
