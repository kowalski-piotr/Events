<?php

/**
 * Zend Framework 2 Events Module
 *
 * @link      http://github.com/pchela/events 
 * @copyright Copyright (c) 20015 Kowalski Piotr (http://www.kowalski-piotr.pl)
 * @license   https://opensource.org/licenses/MIT
 * @since     File available since Release 0.0.1
 */

namespace Events\Controller;

use Events\Entity\Comment;
use Events\Entity\Event;
use Events\Form\CommentForm;
use Events\Form\EventForm;
use Events\Form\SearchForm;
use Events\Service\EventService;
use Events\Service\EventServiceInterface;
use Exception;
use Zend\Mvc\Controller\AbstractActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Events\Controller\IndexController
 * 
 * Główny kontroler modułu 
 */
class IndexController extends AbstractActionController
{

    /**
     * Obiekt EventService odpowiedzialny za logikę aplikacji
     * Service-Layer
     * 
     * @var EventService $eventService
     */
    protected $eventService;

    /**
     * Konstruktor 
     * 
     * @param EventServiceInterface $eventService
     */
    public function __construct(EventServiceInterface $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Akcja odpowiedzialna za wyświetlanie i przeszukiwanie wydarzeń,
     * 
     * @return ViewModel route: /events
     */
    public function indexAction()
    {

        $events = array();
        $errors = array();
        $searchForm = new SearchForm();
        $request = $this->getRequest();
        $page = $this->params()->fromRoute('page', 1);

        //Jeżeli wysłano zapytanie o wyniki wyszukiwania
        //zwracamy tylko wydarzenia spełniające warunki
        if ($request->isPost()) {
            $searchForm->setData($request->getPost());
            if ($searchForm->isValid()) {
                $term = $searchForm->get('search')->getValue();
                try {
                    $events = $this->eventService
                            ->searchEvents($term, $offset, $limit);
                } catch (Exception $e) {
                    // TODO: Log exception
                    // TODO: Translation
                    $errors[] = "Coś poszło nie tak :( Prosimy spróbować za chwilę";
                }
            }
        } else {
            //Jeżeli nie wysłano zapytania o wyniki wyszukiwania 
            //zwracamy wszystkie dla danej strony
            $events = $this->eventService->getAllEvents();
        }

        return new ViewModel(array(
            'events' => $events,
            'form' => $searchForm,
            'error' => $errors,
        ));
    }

    /**
     * Akcja odpowiedzialna za dodawanie nowych wydarzeń, 
     * 
     * @return ViewModel route: /events/add
     */
    public function addAction()
    {
        $errors = array();
        $eventForm = new EventForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $event = new Event();
            $eventForm->bind($event);
            $eventForm->setData($request->getPost());

            if ($eventForm->isValid()) {
                $this->eventService->createCoordinates($event);

                try {
                    $this->eventService->save($event);

                    // wiadomość do administratora o dodaniu wydarzenia 
                    // nie działa na serwerze lokalnym
//                    $this->eventService->sendNotify($event, 'admin@admin.pl');

                    return $this->redirect()->toRoute('events',
                                    array('action' => 'view', 'id' => $event->getId()));
                } catch (Exception $e) {
                    // TODO: Log exception
                    // TODO: Translation
                    $errors[] = "Coś poszło nie tak :( Prosimy spróbować za chwilę";
                }
            }
        }

        return new ViewModel(array(
            'form' => $eventForm,
            'error' => $errors
        ));
    }

    /**
     * Akcja odpowiedzialna za wyświetlanie szczegółowych informacji 
     * o wydarzeniu oraz dodawanie komentarzy,
     * 
     * @return ViewModel ($event, $commentForm)
     */
    public function viewAction()
    {
        $errors = array();
        $commentForm = new CommentForm();
        $request = $this->getRequest();

        $id = (int) $this->params()->fromRoute('id', 0);

        // jeżeli nie podano identyfikatora w adresie, 
        // przekieruj do strony tworzenia nowego wydarzenia
        if (!$id) {
            return $this->redirect()->toRoute('events', array('action' => 'add'));
        }

        // jeżeli podano niepoprawny identyfikator w adresie, 
        // przekieruj do strony tworzenia nowego wydarzenia
        $event = $this->eventService->getEvent($id);
        if (!$event instanceof Event) {
            return $this->redirect()->toRoute('events', array('action' => 'add'));
        }

        // zapisujemy komentarz jeżeli go komentarz
        if ($request->isPost()) {

            $comment = new Comment();
            $commentForm->bind($comment);
            $commentForm->setData($request->getPost());

            if ($commentForm->isValid()) {
                $event->addComment($comment);

                // zapisujemy IP użytkownika dodającego komentarz
                $userIp = $request->getServer()->get('REMOTE_ADDR');
                $comment->setIp($userIp);

                try {
                    $this->eventService->save($comment);
                } catch (Exception $e) {
                    // TODO: Log exception
                    // TODO: Translation
                    $errors[] = "Coś poszło nie tak :( Prosimy spróbować za chwilę";
                }
            }
        }

        return new ViewModel(array(
            'event' => $event,
            'form' => $commentForm,
            'error' => $errors
        ));
    }

    /**
     * Akcja odpowiedzialna za usuwanie komentarzy do wydarzenia
     * 
     * @return void 
     */
    public function removeCommentAction()
    {
        // adres poprzednio odwiedzanej strony użytkownika
        $previousUrl = $this->getRequest()->getHeader('Referer')->getUri();

        $id = (int) $this->params()->fromRoute('id', 0);

        // jeżeli nie podano identyfikatora w adresie, 
        // przekieruj do strony tworzenia nowego wydarzenia
        if (!$id) {
            return $this->redirect()->toUrl($previousUrl);
        }

        try {
            // jeżeli podano niepoprawny identyfikator w adresie, 
            // przekieruj do strony tworzenia nowego wydarzenia
            $comment = $this->eventService->getComment($id);
            if (!$comment instanceof Comment) {
                return $this->redirect()->toUrl($previousUrl);
            }
            $this->eventService->remove($comment);
        } catch (Exception $e) {
            // TODO: Log exception
            // TODO: Translation
            $errors[] = "Coś poszło nie tak :( Prosimy spróbować za chwilę";
        }

        return $this->redirect()->toUrl($previousUrl);
    }

}
