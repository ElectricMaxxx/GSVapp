<?php


namespace Event\Controller;


use Event\Doctrine\Orm\Donation;
use Event\Doctrine\Orm\User;

/**
 * Controller to set up and admin for the donation entity.
 *
 * Needs to hook into the lifecycle make the user mapping sane.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class DonationController extends BaseController
{
    public function prePersist($object)
    {
        if (!$object instanceof Donation) {
            return;
        }

        if (!$object->getUser() instanceof User) {
            $user = $this->manager->find('Event\Doctrine\Orm\User', (int) $object->getUser());
            $object->setUser($user);
        }
    }

    public function preUpdate($object)
    {
        if (!$object instanceof Donation) {
            return;
        }

        if (!$object->getUser() instanceof User) {
            $user = $this->manager->find('Event\Doctrine\Orm\User', (int) $object->getUser());
            $object->setUser($user);
        }
    }
}
