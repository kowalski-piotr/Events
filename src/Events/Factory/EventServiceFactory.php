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

use Events\Service\EventService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Events\Factory\EventServiceFactory
 * 
 * Fabryka tworząca obiekt klasy EventService
 */
class EventServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return EventService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new EventService(
                $serviceLocator->get('Events\Mapper\EventMapperInterface')
        );
    }

}
