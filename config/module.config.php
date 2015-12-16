<?php

/**
 * Zend Framework 2 Events Module 
 *
 * @link      https://github.com/pchela/Events
 * @copyright Copyright (c) 2015 Kowalski Piotr (pchel)
 * @license   https://opensource.org/licenses/MIT MIT License
 */
return array(
    'router' => array(
        'routes' => array(
            'events' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/events',
                    'defaults' => array(
                        'controller' => 'Events',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
        ),
        'initializers' => array(
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Events\Controller\Index' => 'Events\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'events/layout' => __DIR__ . '/../view/layout.phtml',
            'events/index' => __DIR__ . '/../view/index.phtml',
        ),
    ),
);
