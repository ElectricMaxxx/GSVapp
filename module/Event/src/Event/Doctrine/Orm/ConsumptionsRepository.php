<?php


namespace Event\Doctrine\Orm;

use Doctrine\ORM\EntityRepository;

/**
 * The repository class for consumption.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ConsumptionsRepository extends EntityRepository
{
    public function getHighscoreByMeal($mealId)
    {
        return $this
            ->createQueryBuilder('c')
            ->select(
                'u.username, u.id as user_id, m.name as meal_name, m.id as meal_id,
                e.name as event_name, e.id as event_id, e.date'
            )
            ->join('c.user', 'u')
            ->join('c.meal', 'm')
            ->join('c.event', 'e')
            ->where('m.id=:mealId')
            ->andWhere('c.currentState=:currentState')
            ->orderBy('c.amountOf', 'ASC')
            ->setParameter('mealId', $mealId)
            ->setParameter('currentState', Consumption::STATE_PAID)
            ->getQuery()
            ->getResult()
            ;
    }
}
