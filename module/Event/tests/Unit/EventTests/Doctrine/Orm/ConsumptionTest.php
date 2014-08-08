<?php


namespace Event\tests\Unit\Event\Doctrine\Orm;


use Event\Doctrine\Orm\User;
use Event\Doctrine\Orm\Consumption;
use Event\Doctrine\Orm\Event;
use Event\Doctrine\Orm\Meal;

class ConsumptionTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Consumption */
    private $consumption;

    /** @var  Meal */
    private $meal = 'Steak';

    public function setUp()
    {
        $this->meal = new Meal();
        $this->consumption = new Consumption();
        $this->consumption->setMeal($this->meal);
        $this->consumption->setUser(new User());
        $this->consumption->setEvent(new Event());
    }

    /**
     * @dataProvider getPriceValues
     */
    public function testPriceInCompleteComputing($amountOf, $mealPrice, $complete)
    {
        $this->meal->setPrice($mealPrice);
        $this->consumption->setAmountOf($amountOf);

        $this->assertEquals($complete, $this->consumption->getPriceInComplete());
    }

    public function getPriceValues()
    {
        return array(
            array(3, 300, 900),
            array(0, 300, 0),
            array(3, 0, 0),
        );
    }

    public function testPriceStabilityInConsumption()
    {
        $this->meal->setPrice(300);
        $this->consumption->setAmountOf(3);

        $this->assertEquals(900, $this->consumption->getPriceInComplete());

        $this->meal->setPrice(500);

        $this->assertEquals(900, $this->consumption->getPriceInComplete());
    }
}
