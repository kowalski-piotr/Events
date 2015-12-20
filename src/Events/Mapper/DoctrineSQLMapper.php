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

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Events\Mapper\EventMapperInterface;

/**
 * Events\Mapper\DoctrineSQLMapper
 * 
 * Implementacja mappera bazy danych 
 */
class DoctrineSQLMapper implements EventMapperInterface
{

    /**
     * Centralny punkt dostępu do wszystkich funkcji Doctrine ORM - fasada
     * 
     * @var EntityManager $entityManger
     */
    protected $entityManger;

    /**
     * Konstruktor (obiekt tworzony przez DoctrineSQLMapperFactory) 
     * 
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManger = $entityManager;
    }

    /**
     * Wyszukuje wydarzenie po ID
     * 
     * @param int $id
     * @return Event $event 
     */
    public function findEvent($id)
    {
        return $this->entityManger->find('Events\Entity\Event', $id);
    }

    /**
     * Wyszukuje wszystkie wydarenia
     * 
     * @return ArrayObject $events
     */
    public function findAllEvents()
    {
        return $this->entityManger->getRepository('Events\Entity\Event')->findAll();
    }

    /**
     * Wyszukuje wydarzenia w zadanej odległości od podanych współrzędnych
     * 
     * @param float $lat
     * @param float $lng
     * @param int $distance
     * @return ArrayObject $events
     */
    public function findEventsInRadius($lat, $lng, $distance = 2)
    {
        $query = "
            SELECT
                subSel2.*
            FROM (
                SELECT
                    sin(subSel.dlat / 2) * 
                    sin(subSel.dlat / 2) + 
                    cos(subSel.lat1) * 
                    cos(subSel.lat2) * 
                    sin(subSel.dlng / 2) * 
                    sin(subSel.dlng / 2) sel,
                    subSel.*
                FROM (
                    SELECT 
                        (radians(:lat)-radians(lat)) dlat, 
                        (radians(:lng)-radians(lng)) dlng, 
                        radians(lat) lat1, 
                        radians(lng) lng1,
                        radians(:lat) lat2,
                        radians(:lng) lng2,
                        Event.*
                    From 
                        Event 
                ) subSel 
            ) subSel2
            WHERE
                (6372.797 * 
                (2 * atan2(sqrt(subSel2.sel), sqrt(1 - subSel2.sel)))) <= :distance
            ";

        $rsm = new ResultSetMappingBuilder($this->entityManger);
        $rsm->addRootEntityFromClassMetadata('Events\Entity\Event', 'event');
        $nativeQuery = $this->entityManger->createNativeQuery($query, $rsm);
        $nativeQuery->setParameters(array(
            'lat' => $lat,
            'lng' => $lng,
            'distance' => $distance,
        ));

        return $nativeQuery->getResult();
    }

    /**
     * Wyszukuje wydarzenia po nazwie, opisie, adresie oraz emailu
     * 
     * @param string $term
     * @return ArrayObject $events
     */
    public function findEventsByTerm($term)
    {

        $query = $this->entityManger->createQueryBuilder()
                ->select('event')
                ->from('Events\Entity\Event', 'event')
                ->where('event.name LIKE :term')
                ->orWhere('event.description LIKE :term')
                ->orWhere('event.address LIKE :term')
                ->orWhere('event.email LIKE :term')
                ->setParameter('term', '%' . $term . '%')
                ->getQuery();

        return $query->getResult();
    }

    /**
     * Wyszukuje komentarz po ID
     * 
     * @param type $id
     * @return Comment $commentt 
     */
    public function findComment($id)
    {
        return $this->entityManger->find('Events\Entity\Comment', $id);
    }

    /**
     * Zapisuje nowy lub zmieniony obiekt w bazie danych
     * 
     * @param object $entity
     * @return object $entity
     */
    public function save($entity)
    {
        $this->entityManger->persist($entity);
        $this->entityManger->flush();
    }

    /**
     * Usuwa obiekt z bazy danych
     * 
     * @param object $entity
     * @return void
     */
    public function remove($entity)
    {
        $this->entityManger->remove($entity);
        $this->entityManger->flush($entity);
    }
}
