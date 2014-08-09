<?php


namespace Event\Controller;

use Event\Doctrine\Orm\Event;

/**
 * Controller class for the events.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class EventController extends BaseController
{
    protected function prePersist($object)
    {
        if (!$object instanceof Event) {
            return;
        }

        $date = $object->getDate();
        if ($date instanceof \DateTime) {
            return;
        }

        $object->setDate(new \DateTime((string) $date, new \DateTimeZone('Europe/Berlin')));

        if (null === $object->getName() || '' === $object->getName()) {
            $object->setName('Grillen am: '.$object->getDate()->format('d.m.Y'));
        }
    }

    protected function postLoad($object)
    {
        if ($object instanceof Event && $object->getDate() instanceof \DateTime) {
            $date = $object->getDate();
            $object->setDate($date->format('d.m.Y'));
        }
    }

    protected function preUpdate($object)
    {
        if (!$object instanceof Event) {
            return;
        }

        $date = $object->getDate();
        if ($date instanceof \DateTime) {
            return;
        }

        $object->setDate(new \DateTime((string) $date, new \DateTimeZone('Europe/Berlin')));

        if (null === $object->getName() || '' === $object->getName()) {
            $object->setName('Grillen am: '.$object->getDate()->format('d.m.Y'));
        }
    }
}