<?php


namespace Event\Controller;

use Zend\View\Model\ViewModel;

/**
 * The DashboardController is responsible for displaying several
 * blocks on the dashboard. But it will get help from some block services.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class DashboardController extends BaseController
{

    /**
     * todo[max] Implement some block services to display some statistics as
     *           blocks on dashboard. By the block services the content will be reuseable
     *           in the statistics view and on dashboard.
     *
     * @return array|ViewModel
     */
    public function indexAction()
    {
        return $this->renderView('index', array());
    }
}
