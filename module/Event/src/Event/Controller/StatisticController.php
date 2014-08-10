<?php


namespace Event\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use Event\Doctrine\Orm\Event;
use Event\Model\CashBox;

class StatisticController extends BaseController
{
    public function steakHighscoreAction()
    {
        return $this->renderView('steak-highscore', array(
            'title' => 'Hier erscheint bald der Highscore'
        ));
    }

    public function eventsAction()
    {
        $events = $this->manager->getRepository('Event\Doctrine\Orm\Event')->findAll();

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
                        'name' => $consumption->getMeal()->getName(),
                        'price' => $consumption->getMeal()->getPrice());
                    continue;
                }

                $meals[$event->getId()][$id]['count'] += $consumption->getAmountOf();
            }
        }

        return $meals;
    }

    public function cashBoxAction()
    {
        $events = $this->manager->getRepository('Event\Doctrine\Orm\Event')->findAll();
        $cashBox = new CashBox();
        $cashBox->setEvents(new ArrayCollection($events));
        return $this->renderView('cashBox', array(
            'title'  => 'Die Kasse im Überblick',
            'cashBox' => $cashBox,
        ));
    }
}
