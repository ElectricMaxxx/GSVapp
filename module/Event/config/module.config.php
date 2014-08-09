<?php
/**
 * Base configuration file for module Event
 */

use Event\Controller\MealController;
use Event\Controller\UserController;
use Event\Form\Filter\Meal;
use Event\Form\Filter\User as UserFilter;
use Event\Form\Meal as MealObject;
use Event\Form\User;
use Zend\Mvc\Controller\ControllerManager;

return array(
    'controllers' => array(
        'factories' => array(
            'Event\Controller\User' => function (ControllerManager $cm) {
                $serviceLocator = $cm->getServiceLocator();
                $controller = new UserController();
                $controller->setManager($serviceLocator->get('doctrine.entitymanager.orm_default'));
                $controller->setBaseRoutePattern('user');
                $controller->setClassName('Event\Doctrine\Orm\User');
                $controller->setForm(new User('user'));
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
