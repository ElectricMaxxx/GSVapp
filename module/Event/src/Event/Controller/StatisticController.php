<?php


namespace Event\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use Event\Doctrine\Orm\Consumption;
use Event\Doctrine\Orm\Event;
use Event\Model\CashBox;

class StatisticController extends BaseController
{
    /**
     * When no other meal id is given, the highscore is created by
     * this one here.
     *
     * Normally an id should be provided by the request - select box for the "filter".
     */
    const DEFAULT_MEAL_ID = 2;

    public function highscoreAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $meal = $this->manager->find('Event\Doctrine\Orm\Meal', $id);
        $mealId = $meal ? $meal->getId() : self::DEFAULT_MEAL_ID;
        $persistedConsumptions = $this->manager
            ->getRepository('Event\Doctrine\Orm\Consumption')
            ->getHighscoreByMeal($mealId);
        $consumptions = array();
        foreach ($persistedConsumptions as $consumption) {
            $consumptions[$consumption[1]][] = $consumption;
        }

        krsort($consumptions);
        return $this->renderView('highscore', array(
            'title'        => 'Hier erscheint bald der Highscore',
            'user_consumptions' => $consumptions,
            'meals'             => $this->manager
                ->getRepository('Event\Doctrine\Orm\Meal')
                ->findAll(),
            'mealId' => $mealId
        ));
    }

    public function eventsAction()
    {
        $events = $this->manager->getRepository('Event\Doctrine\Orm\Event')->findBy(array(), array('date' => 'ASC'));

        return $this->renderView('events', array(
            'title'  => 'Zusammenfassung der Grillveranstaltungen',
            'events' => $events,
            'meals'  => $this->createMealsList($events),
        ));
    }

    /**
     * @param Event[] $events
     * @return array
     */
    private function createMealsList($events)
    {
        $meals = array();
        foreach ($events as $event) {
            $meals[$event->getId()] = array();
            foreach ($event->getConsumptions()->toArray() as $consumption) {
                $id = $consumption->getMeal()->getId();
                if (!array_key_exists($id, $meals[$event->getId()])) {
                    $meals[$event->getId()][$id] = array(
                        'count' => $consumption->getAmountOf(),
                        'name'  => $consumption->getMeal()->getName(),
                        'price' => $consumption->getCurrentPriceOfMeal());
                    continue;
                }

                $meals[$event->getId()][$id]['count'] += $consumption->getAmountOf();
            }
        }

        return $meals;
    }

    public function cashBoxAction()
    {
        $events = $this->manager->getRepository('Event\Doctrine\Orm\Event')->findBy(array(), array('date' => 'ASC'));
        $donations = $this->manager->getRepository('Event\Doctrine\Orm\Donation')->findAll();
        $cashBox = new CashBox();
        $cashBox->setEvents(new ArrayCollection($events));
        $cashBox->setDonations(new ArrayCollection($donations));
        return $this->renderView('cashBox', array(
            'title'  => 'Die Kasse im Überblick',
            'cashBox' => $cashBox,
        ));
    }
}
