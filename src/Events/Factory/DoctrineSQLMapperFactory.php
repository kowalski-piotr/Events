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

use Events\Mapper\DoctrineSQLMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Events\Factory\DoctrineSQLMapperFactory
 * 
 * Fabryka tworząca obiekt klasy DoctrineSQLMapper
 */
class DoctrineSQLMapperFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return DoctrineSQLMapper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new DoctrineSQLMapper(
                $serviceLocator->get('Doctrine\ORM\EntityManager')
        );
    }

}
