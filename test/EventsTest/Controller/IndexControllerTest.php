<?php

/**
 * Zend Framework 2 Events Module
 *
 * Long description for file (if any)...
 *
 * @link      http://github.com/pchela/events 
 * @copyright Copyright (c) 20015 Kowalski Piotr (http://www.kowalski-piotr.pl)
 * @license   https://opensource.org/licenses/MIT
 * @since     File available since Release 0.0.1
 */
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Description of IndexControllerTest
 *
 * @author Kowalski Piotr <kowalski.piotr@primemotion.pl>
 */
class IndexControllerTest extends AbstractHttpControllerTestCase
{

    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
                include 'config' . DIRECTORY_SEPARATOR . 'application.config.php'
        );
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/events');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Events');
        $this->assertControllerName('events\controller\index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('events');
    }

    public function testAddAction()
    {
        $data = array(
            'name' => 'Wydarzenie - Test',
            'description' => 'Wydarzenie - Test',
            'address' => 'Wydarzenie - Test',
            'email' => 'Wydarzenie - Test',
            'fromDate' => 'Wydarzenie - Test',
            'toDate' => 'Wydarzenie - Test',
            
        );
        $this->dispatch('/events/add', 'POST', $data);
    }

}
