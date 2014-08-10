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
            ->Select(
                'u.username, u.id as user_id, SUM(c.amountOf), m.name as meal_name'
            )
            ->join('c.user', 'u')
            ->join('c.meal', 'm')
            ->where('m.id=:mealId')
            ->andWhere('c.currentState=:currentState')
            ->groupBy('u.id')
            ->setParameter('mealId', $mealId)
            ->setParameter('currentState', Consumption::STATE_PAID)
            ->getQuery()
            ->getResult()
            ;
    }
}
