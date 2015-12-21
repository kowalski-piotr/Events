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
     * Zwraca wydarzenie o podanym ID
     * 
     * @param int $id
     * @return Event $event 
     */
    public function getEvent($id);

    /**
     * Zwraca wszystkie wydarzenia
     * 
     * @return ArrayObject $events
     */
    public function getAllEvents();

    /**
     * Zwraca wydarzenia pasujące do podanego wyrażenia:
     * w zadanej odległości od podanego adresu,
     * po nazwie, 
     * po opisie, 
     * po adresie 
     * po adresie email użytkownika, który dodał wydarzenie
     * 
     * @param string $term
     * @param int $distance
     * @return ArrayObject $events
     */
    public function searchEvents($term, $distance);

    /**
     * Wyszukuje współrzędne dla podanego adresu z wykorzystaniem
     * Google Maps Api i wstawia je do Wydarzenia 
     * 
     * @param Event $event
     * @return Event $event
     */
    public function createCoordinates(Event $event);

    /**
     * Zwraca komentarz do wydarzenia po ID
     * 
     * @param type $id
     * @return Comment $commentt 
     */
    public function getComment($id);

    /**
     * Wysyła wiadomość pod podany adres email ze szczegółami wydarzenia
     * 
     * @param Event $event
     */
    public function sendNotify(Event $event, $recipient);

    /**
     * Zapisuje nowy lub zmieniony obiekt
     * 
     * @param object $entity
     * @return object $entity
     */
    public function save($entity);

    /**
     * Usuwa obiekt
     * 
     * @param object $entity
     * @return void
     */
    public function remove($entity);
}
