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
                'type' => 'Literal',
                'options' => array(
                    'route' => '/events',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Events\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'detail' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/:id',
                            'defaults' => array(
                                'action' => 'detail'
                            ),
                            'constraints' => array(
                                'id' => '\d+'
                            )
                        )
                    ),
                    'add' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'controller' => 'Events\Controller\Index',
                                'action' => 'add'
                            )
                        )
                    )
                )
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Events\Service\EventServiceInterface' => 'Events\Factory\EventServiceFactory',
            'Events\Mapper\EventMapperInterface'   => 'Events\Factory\DoctrineSQLMapperFactory',
        )
    ),
    'controllers' => array(
        'factories' => array(
            'Events\Controller\Index' => 'Events\Factory\IndexControllerFactory',
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'events' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            'entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/Events/Entity',
                ),
            ),
            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => array(
                'drivers' => array(
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                    'Events\Entity' => 'entities'
                )
            )
        )
    )
);
