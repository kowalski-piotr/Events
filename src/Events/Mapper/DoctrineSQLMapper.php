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
     * {@inheritDoc}
     */
    public function findEvent($id)
    {
        return $this->entityManger->find('Events\Entity\Event', $id);
    }

    /**
     * {@inheritDoc}
     */
    public function findAllEvents()
    {
        return $this->entityManger->getRepository('Events\Entity\Event')->findAll();
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function findComment($id)
    {
        return $this->entityManger->find('Events\Entity\Comment', $id);
    }

    /**
     * {@inheritDoc}
     */
    public function save($entity)
    {
        $this->entityManger->persist($entity);
        $this->entityManger->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function remove($entity)
    {
        $this->entityManger->remove($entity);
        $this->entityManger->flush($entity);
    }

//    public function findNearEvents($lat1, $lng1, $distance, $term = null)
//    {
//        $query = "
//            SELECT
//                subSel2.*
//            FROM (
//                SELECT
//                    sin(subSel.dlat / 2) * 
//                    sin(subSel.dlat / 2) + 
//                    cos(subSel.lat1) * 
//                    cos(subSel.lat2) * 
//                    sin(subSel.dlng / 2) * 
//                    sin(subSel.dlng / 2) sel,
//                    subSel.*
//                FROM (
//                    SELECT 
//                        (radians($lat1)-radians(lat)) dlat, 
//                        (radians($lng1)-radians(lng)) dlng, 
//                        radians(lat) lat1, 
//                        radians(lng) lng1,
//                        radians($lat1) lat2,
//                        radians($lng1) lng2,
//                        Event.*
//                    From 
//                        Event 
//                ) subSel 
//            ) subSel2
//            WHERE
//                (6372.797 * 
//                (2 * atan2(sqrt(subSel2.sel), sqrt(1 - subSel2.sel)))) <= $distance
//                    OR
//                subSel2.name LIKE '%$term%'
//                    OR
//                subSel2.description LIKE '%$term%'
//                    OR
//                subSel2.address LIKE '%$term%'
//                    OR
//                subSel2.email LIKE '%$term%'
//            ";
//
//        $rsm = new ResultSetMappingBuilder($this->entityManger);
//        $rsm->addRootEntityFromClassMetadata('Events\Entity\Event', 'event');
//        $nativeQuery = $this->entityManger->createNativeQuery($query, $rsm);
//
//        return $nativeQuery->getResult();
//    }
}
