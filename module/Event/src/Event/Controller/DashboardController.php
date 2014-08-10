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
        return $this->renderView('index', array());
    }
}
