<?php

/**
 * Zend Framework 2 Events Module
 *
 * @link      http://github.com/pchela/events 
 * @copyright Copyright (c) 20015 Kowalski Piotr (http://www.kowalski-piotr.pl)
 * @license   https://opensource.org/licenses/MIT
 * @since     File available since Release 0.0.1
 */

namespace Events\Mapper;

use ArrayObject;
use Events\Entity\Comment;
use Events\Entity\Event;

/**
 * Events\Mapper\EventMapperInterface
 * 
 * Interfejs abstrakcyjnej warstwy bazy danych (Mapper-Layer)
 */
interface EventMapperInterface
{

    /**
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
     * Wyszukuje wydarzenia w zadanej odległości od podancyh współrzędnych
     * 
     * @param float $lat
     * @param float $lng
     * @param int $distance
     * @return ArrayObject $events
     */
    public function findEventsInRadius($lat, $lng, $distance = 2);

    /**
     * Wyszukuje wydarzenia po nazwie, opisie, adresie oraz emailu
     * 
     * @param string $term
     * @return ArrayObject $events
     */
    public function findEventsByTerm($term);

    /**
     * @param type $id
     * @return Comment $commentt 
     */
    public function findComment($id);

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
