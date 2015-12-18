<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonEvents for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Events\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Events\Service\EventServiceInterface;
use Events\Form\EventForm;

class IndexController extends AbstractActionController
{

    protected $eventService;
    protected $eventForm;

    public function __construct(
            EventServiceInterface $eventService, 
            EventForm $eventForm)
    {
        $this->eventService = $eventService;
        $this->eventForm = $eventForm;
    }

    public function indexAction()
    {
//        $events = $this->entityManager->getRepository('Events\Entity\Event')->findAll();
        return new ViewModel(array('events' => $events));
    }

    public function addAction()
    {
        $request = $this->getRequest();

        if ($request->isPost())
        {
            $event = new \Events\Entity\Event();
            $this->eventForm->bind($event);
            $this->eventForm->setData($request->getPost());

            if ($this->eventForm->isValid())
            {
                try
                {
                    $this->eventService->saveEvent($event);

                    return $this->redirect()->toRoute('events');
                } catch (\Exception $e)
                {
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return new ViewModel(array(
            'form' => $this->eventForm
        ));
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $event = $this->getObjectManager()->find('\Events\Entity\Event', $id);
        if ($this->request->isPost())
        {
            $event->setFullName($this->getRequest()->getPost('fullname'));
            $this->getObjectManager()->persist($event);
            $this->getObjectManager()->flush();
            return $this->redirect()->toRoute('home');
        }
        return new ViewModel(array('event' => $event));
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $event = $this->getObjectManager()->find('\Events\Entity\Event', $id);
        if ($this->request->isPost())
        {
            $this->getObjectManager()->remove($event);
            $this->getObjectManager()->flush();
            return $this->redirect()->toRoute('home');
        }
        return new ViewModel(array('event' => $event));
    }

    protected function getObjectManager()
    {
        if (!$this->_objectManager)
        {
            $this->_objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->_objectManager;
    }

}
