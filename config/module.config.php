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
                'type' => 'segment',
                'options' => array(
                    'route' => '/events[/:action][/:id][/page/:page]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'page' => '[0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Events\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
//                'child_routes' => array(
//                    'detail' => array(
//                        'type' => 'segment',
//                        'options' => array(
//                            'route' => '/:id',
//                            'defaults' => array(
//                                'action' => 'detail'
//                            ),
//                            'constraints' => array(
//                                'id' => '\d+'
//                            )
//                        )
//                    ),
//                    'add' => array(
//                        'type' => 'literal',
//                        'options' => array(
//                            'route' => '/add',
//                            'defaults' => array(
//                                'controller' => 'Events\Controller\Index',
//                                'action' => 'add'
//                            )
//                        )
//                    ),
//                    'view' => array(
//                        'type' => 'segment',
//                        'options' => array(
//                            'route' => '/view/:id',
//                            'defaults' => array(
//                                'action' => 'view'
//                            ),
//                            'constraints' => array(
//                                'id' => '\d+'
//                            )
//                        ),
//                    )
//                )
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Events\Service\EventServiceInterface' => 'Events\Factory\EventServiceFactory',
            'Events\Mapper\EventMapperInterface' => 'Events\Factory\DoctrineSQLMapperFactory',
        ),
//        'invokables' => array(
//            'Events\Service\MapsServiceInterface' => 'Events\Service\GoogleMapsService',
//        )
    ),
    'controllers' => array(
        'factories' => array(
            'Events\Controller\Index' => 'Events\Factory\IndexControllerFactory',
        )
    ),
    'module_layouts' => array(
        'Events' => 'events/layout',
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'events' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'events/layout' => __DIR__ . '/../view/layout/layout.phtml',
//            'error/404' => __DIR__ . '/../view/error/404.phtml',
//            'error/index' => __DIR__ . '/../view/error/index.phtml',
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
