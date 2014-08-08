<?php

namespace Event\tests\Unit\Event\Doctrine\Orm;

use Event\Doctrine\Orm\Consumption;
use Event\Doctrine\Orm\Event;
use Event\Doctrine\Orm\Meal;

class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Consumption
     */
    private $consumption;

    /**
     * @var Event
     */
    private $event;

    public function setUp()
    {
        $this->event = new Event();
        $this->event->setName('Some event');
        $this->event->setDate(new \DateTime(null, new \DateTimeZone('Europe/Berlin')));

        $this->consumption = new Consumption();
    }

    public function testDefaultName()
    {
        $event = new Event();
        $date = new \DateTime('now', new \DateTimeZone('Europe/Berlin'));
        $event->setDate($date);

        $this->assertEquals('Grillen am '.$date->format('d.m.Y'), $event->getName());
    }

    /**
     * @param $consumptions
     * @param $value
     * @param $receivable
     * @dataProvider getConsumptions
     */
    public function testPriceComputing($consumptions, $value, $receivable)
    {
        foreach ($consumptions as $consumption) {
            $this->event->addConsumption($consumption);
        }

        $this->assertEquals($value, $this->event->getPriceInComplete());
        $this->assertEquals($value, $this->event->getReceivables());

        $this->event->getConsumptions()->first()->setCurrentState(Consumption::STATE_PAID);

        $this->assertEquals($value, $this->event->getPriceInComplete());
        $this->assertEquals($receivable, $this->event->getReceivables());
    }

    public function getConsumptions()
    {
        $steak = new Meal();
        $steak->setPrice(500);
        $soup = new Meal();
        $soup->setPrice(250);

        $consumptionWithAmountNull = new Consumption($steak, 0);
        $consumptionsOne = new Consumption($steak, 3);
        $consumptionTwo = new Consumption($soup, 1);

        return array(
            array(array($consumptionWithAmountNull), 0, 0),
            array(array($consumptionsOne, $consumptionTwo), 1750, 250),
        );
    }

    public function testBalancing()
    {
        // preconditions
        $steak = new Meal();
        $steak->setPrice(500);
        $this->consumption->setMeal($steak);
        $this->consumption->setAmountOf(3);
        $this->event->addConsumption($this->consumption);

        // assertion
        $this->assertFalse($this->event->isBalanced());

        // change condition
        $this->consumption->setCurrentState(Consumption::STATE_PAID);

        $this->assertTrue($this->event->isBalanced());
    }

    public function testEventWithNoConsumption()
    {
        $this->assertTrue($this->event->isBalanced());
        $this->assertEquals($this->event->getPriceInComplete(), 0);
    }
}
