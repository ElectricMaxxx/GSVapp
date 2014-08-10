<?php


namespace Event\Controller;

/**
 * The DashboardController is responsible for displaying several
 * blocks on the dashboard. But it will get help from some block services.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class DashboardController extends BaseController
{

    public function indexAction()
    {
        $blocks = array();
        #$blocks[] = $this->createHighscoreAsView(2);

        return $this->renderView('index', array());
    }

    protected function createHighscoreAsView($mealId)
    {
        return array(
                'title' => 'Die besten Steakesser der verangenen Events',
                'message' => 'Nur die bezahlten Steaks sind aufgeführt',
                'list'    => $this->manager
                        ->getRepository('Event\Doctrine\Orm\Consumption')
                        ->getHighscoreByMeal($mealId),
            );
    }
}
