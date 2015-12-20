<?php

/**
 * Zend Framework 2 Events Module
 *
 * @link      http://github.com/pchela/events 
 * @copyright Copyright (c) 20015 Kowalski Piotr (http://www.kowalski-piotr.pl)
 * @license   https://opensource.org/licenses/MIT
 * @since     File available since Release 0.0.1
 */

namespace Events\Service;

use ArrayObject;
use Events\Entity\Comment;
use Events\Entity\Event;

/**
 * Events\Service\EventServiceInterface
 * 
 * Interfejs warstwy biznesowej aplikacji
 */
interface EventServiceInterface
{

    /**
     * Wyszukuje wydarzenie po ID
     * 
     * @param int $id
     * @return Event $event 
     */
    public function findEvent($id);

    /**
     * Zwraca wszystkie wydarenia
     * 
     * @return ArrayObject $events
     */
    public function findAllEvents();

    /**
     * Wyszukuje wydarzenia:
     * w zadanej odległości od podancyh współrzędnych,
     * po nazwie, 
     * opisie, 
     * adresie 
     * oraz adresie email
     * 
     * @param string $term
     * @param int $distance
     * @return ArrayObject $events
     */
    public function searchEvent($term, $distance);
    
    /**
     * Wyszukuje współrzędne dla podanego adresu z wykorzystaniem
     * Google Maps Api i ustawia wstawia je do Wydarzenia 
     * 
     * @param Event $event
     * @return Event $event
     */
    public function createCoordinates(Event $event);

    /**
     * Wyszukuje komentarz do wydarzenia po ID
     * 
     * @param type $id
     * @return Comment $commentt 
     */
    public function findComment($id);

    /**
     * Wysyła wiadomość do administratora strony o dodanym wydarzeniu
     * 
     * @param Event $event
     */
    public function sendNotify(Event $event);

    /**
     * Zapisuje nowy lub zmieniony obiekt bazie danych
     * 
     * @param object $entity
     * @return object $entity
     */
    public function save($entity);

    /**
     * Usuwa obiekt z bazy danych
     * 
     * @param object $entity
     * @return void
     */
    public function remove($entity);
}
