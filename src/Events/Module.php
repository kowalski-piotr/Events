<?php

/**
 * Zend Framework 2 Events Module 
 *
 * @link      https://github.com/pchela/Events
 * @copyright Copyright (c) 2015 Kowalski Piotr (pchel)
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace Events;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements AutoloaderProviderInterface
{

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => '../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
