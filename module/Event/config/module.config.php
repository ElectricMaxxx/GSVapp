<?php
/**
 * Base configuration file for module Event
 */

use Event\Controller\DashboardController;
use Event\Controller\DonationController;
use Event\Controller\EventController;
use Event\Controller\MealController;
use Event\Controller\StatisticController;
use Event\Controller\UserController;
use Event\Form\Donation as DonationForm;
use Event\Form\Filter\Donation as DonationFilter;
use Event\Form\Event as EventForm;
use Event\Form\Filter\Event as EventFilter;
use Event\Form\Filter\Meal;
use Event\Form\Filter\User as UserFilter;
use Event\Form\Meal as MealObject;
use Event\Form\User;
use Zend\Mvc\Controller\ControllerManager;

return array(
    'controllers' => array(
        'invokables' => array(),
        'factories' => array(
            'Event\Controller\User' => function (ControllerManager $cm) {
                $serviceLocator = $cm->getServiceLocator();
                $controller = new UserController();
                $controller->setManager($serviceLocator->get('doctrine.entitymanager.orm_default'));
                $controller->setBaseRoutePattern('user');
                $controller->setClassName('Event\Doctrine\Orm\User');
                $controller->setForm(new User('user', $serviceLocator->get('doctrine.entitymanager.orm_default')));
                $controller->setInputFilter(new UserFilter());

                return $controller;
            },
            'Event\Controller\Meal' => function (ControllerManager $cm) {
                    $serviceLocator = $cm->getServiceLocator();
                    $controller = new MealController();
                    $controller->setManager($serviceLocator->get('doctrine.entitymanager.orm_default'));
                    $controller->setBaseRoutePattern('meal');
                    $controller->setClassName('Event\Doctrine\Orm\Meal');
                    $controller->setForm(new MealObject('meal'));
                    $controller->setInputFilter(new Meal());

                    return $controller;
            },
            'Event\Controller\Event' => function (ControllerManager $cm) {
                    $serviceLocator = $cm->getServiceLocator();
                    $controller = new EventController();
                    $controller->setManager($serviceLocator->get('doctrine.entitymanager.orm_default'));
                    $controller->setBaseRoutePattern('event');
                    $controller->setClassName('Event\Doctrine\Orm\Event');
                    $controller->setForm(new EventForm('meal', $serviceLocator->get('doctrine.entitymanager.orm_default')));
                    $controller->setInputFilter(new EventFilter());

                    return $controller;
            },
            'Event\Controller\Donation' => function (ControllerManager $cm) {
                    $serviceLocator = $cm->getServiceLocator();
                    $controller = new DonationController();
                    $controller->setManager($serviceLocator->get('doctrine.entitymanager.orm_default'));
                    $controller->setBaseRoutePattern('donation');
                    $controller->setClassName('Event\Doctrine\Orm\Donation');
                    $controller->setForm(new DonationForm('donation', $serviceLocator->get('doctrine.entitymanager.orm_default')));
                    $controller->setInputFilter(new DonationFilter());

                    return $controller;
                },
            'Event\Controller\Dashboard' => function (ControllerManager $cm) {
                    $serviceLocator = $cm->getServiceLocator();
                    $controller = new DashboardController();
                    $controller->setManager($serviceLocator->get('doctrine.entitymanager.orm_default'));
                    $controller->setBaseRoutePattern('dashboard');

                    return $controller;
            },
            'Event\Controller\Statistic' => function (ControllerManager $cm) {
                    $serviceLocator = $cm->getServiceLocator();
                    $controller = new StatisticController();
                    $controller->setManager($serviceLocator->get('doctrine.entitymanager.orm_default'));
                    $controller->setBaseRoutePattern('statistic');

                    return $controller;
            },
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'meal' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/meal[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Event\Controller\Meal',
                        'action'     => 'list',
                    ),
                ),
            ),
            'user' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/user[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Event\Controller\User',
                        'action'     => 'list',
                    ),
                ),
            ),
            'donation' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/donation[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Event\Controller\Donation',
                        'action'     => 'list',
                    ),
                ),
            ),
            'event' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/event[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Event\Controller\Event',
                        'action'     => 'list',
                    ),
                ),
            ),
            'dashboard' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/dashboard',
                    'defaults' => array(
                        'controller' => 'Event\Controller\Dashboard',
                        'action'     => 'index',
                    ),
                ),
            ),
            'statistic' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/statistic[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Event\Controller\Statistic',
                        'action'     => 'cashBox',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'event' => __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'EventDriver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Event/Doctrine/Orm')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Event\Doctrine\Orm' => 'EventDriver'
                )
            )
        )
    )
);
