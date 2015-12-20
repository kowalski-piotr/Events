<?php

/**
 * Zend Framework 2 Events Module
 *
 * @link      http://github.com/pchela/events 
 * @copyright Copyright (c) 20015 Kowalski Piotr (http://www.kowalski-piotr.pl)
 * @license   https://opensource.org/licenses/MIT
 * @since     File available since Release 0.0.1
 */

namespace Events\Factory;

use Events\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Events\Factory\IndexControllerFactory
 * 
 * Fabryka tworząca obiekt klasy IndexController
 */
class IndexControllerFactory implements FactoryInterface
{

    /**
     * Create service
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return IndexController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sm = $serviceLocator->getServiceLocator();
        return new IndexController(
                $sm->get('Events\Service\EventServiceInterface')
        );
    }

}
