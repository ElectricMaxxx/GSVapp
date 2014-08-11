<?php


namespace Event\Controller;

use Event\Doctrine\Orm\Event;

/**
 * Controller class for serving an admin for the Event entity.
 *
 * Needs to hook into the lifecycle to transform date into \DateTime and back.
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
        if (!$date instanceof \DateTime) {
            $object->setDate(new \DateTime((string) $date, new \DateTimeZone('Europe/Berlin')));
        }

        // is enough to call once
        $object->getName();
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
        if (!$date instanceof \DateTime) {
            $object->setDate(new \DateTime((string) $date, new \DateTimeZone('Europe/Berlin')));
        }

        // is enough to call once
        $object->getName();
    }
}
