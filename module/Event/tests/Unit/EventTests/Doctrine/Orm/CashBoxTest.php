<?php


namespace Event\tests\Unit\Event\Doctrine\Orm;


use Doctrine\Common\Collections\ArrayCollection;
use Event\Doctrine\Orm\Consumption;
use Event\Doctrine\Orm\Donation;
use Event\Doctrine\Orm\Event;
use Event\Doctrine\Orm\Meal;
use Event\Model\CashBox;

class CashBoxTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CashBox
     */
    private $cashBox;

    public function setUp()
    {
        $this->cashBox = new CashBox();
    }

    /**
     * @dataProvider getEventAndDonations
     */
    public function testPriceComputing(array $events, array $donations, $receivables, $donation, $balance)
    {
        $this->cashBox->setEvents(new ArrayCollection($events));
        $this->cashBox->setDonations(new ArrayCollection($donations));

        $this->assertEquals($receivables, $this->cashBox->getReceivables());
        $this->assertEquals($receivables, $this->cashBox->getReceivables());
        $this->assertEquals($donation, $this->cashBox->getDonationsInComplete());

        $this->assertEquals($balance, $this->cashBox->isBalanced());
    }

    public function getEventAndDonations()
    {
        $steak = new Meal();
        $steak->setPrice(500);
        $soup = new Meal();
        $soup->setPrice(250);

        $consumptionWithAmountNull = new Consumption($steak, 0);
        $consumptionsOne = new Consumption($steak, 3);
        $consumptionTwo = new Consumption($soup, 1);

        $eventOne = new Event();
        $eventOne->addConsumption($consumptionWithAmountNull);

        $eventTwo = new Event();
        $eventTwo->addConsumption($consumptionsOne);
        $eventTwo->addConsumption($consumptionTwo);

        $donationOne = new Donation();
        $donationOne->setValue(500);

        $donationTwo = new Donation();
        $donationTwo->setValue(1750);

        $donationThree = new Donation();
        $donationThree->setValue(5000);

        return array(
            array(array($eventOne), array(), 0, 0, true),
            array(array($eventTwo), array($donationOne), 1750, 500, false),
            array(array($eventTwo), array($donationTwo), 1750, 1750, false),
            array(array($eventTwo), array($donationThree), 1750, 5000, false),
        );
    }

    public function testEventLifecycleInCashBox()
    {
        // pre conditions
        $steak = new Meal();
        $steak->setPrice(500);
        $salad = new Meal();
        $salad->setPrice(450);

        $consumptionOne = new Consumption($salad, 1);
        $consumptionTwo = new Consumption($steak, 3);

        $event = new Event();
        $event->setConsumptions(new ArrayCollection(array($consumptionOne, $consumptionTwo)));

        $donation = new Donation();
        $donation->setValue(5000);

        $this->cashBox->setEvents(new ArrayCollection(array($event)));
        $this->cashBox->setDonations(new ArrayCollection(array($donation)));

        // assertions to check the state at the beginning
        $this->assertEquals(1950, $this->cashBox->getPriceInComplete());
        $this->assertEquals(1950, $this->cashBox->getReceivables());
        $this->assertEquals(5000, $this->cashBox->getDonationsInComplete());
        $this->assertFalse($this->cashBox->isBalanced());

        // first consumption get paid
        $consumptionOne->setCurrentState(Consumption::STATE_PAID);

        $this->assertEquals(1950, $this->cashBox->getPriceInComplete());
        $this->assertEquals(1500, $this->cashBox->getReceivables());
        $this->assertEquals(5000, $this->cashBox->getDonationsInComplete());
        $this->assertFalse($this->cashBox->isBalanced());

        // second consumption get paid
        $consumptionTwo->setCurrentState(Consumption::STATE_PAID);
        $this->assertEquals(1950, $this->cashBox->getPriceInComplete());
        $this->assertEquals(0, $this->cashBox->getReceivables());
        $this->assertEquals(5000, $this->cashBox->getDonationsInComplete());
        $this->assertTrue($this->cashBox->isBalanced());
    }
}
