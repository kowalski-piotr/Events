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

use Events\Entity\Event;
use Events\Mapper\EventMapperInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;

/**
 * Events\Controller\IndexController
 * 
 * Warstwa logiki biznesowej aplikacji 
 */
class EventService implements EventServiceInterface
{

    /**
     * Google Maps Api, funkcja zwracająca informację o podanym adresie
     * 
     * @var string $url 
     */
    private $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=";

    /**
     * Abstrakcyjna warstwa bazy danych (Mapper-Layer)
     * 
     * @var EventMapperInterface
     */
    protected $eventMapper;

    /**
     * Konstruktor
     *
     * @param EventMapperInterface $eventMapper
     */
    public function __construct(EventMapperInterface $eventMapper)
    {
        $this->eventMapper = $eventMapper;
    }

    /**
     * {@inheritDoc}
     */
    public function findEvent($id)
    {
        return $this->eventMapper->findEvent($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findAllEvents()
    {
        return $this->eventMapper->findAllEvents();
    }

    /**
     * {@inheritDoc}
     */
    public function searchEvent($term, $distance = 2)
    {
        $coordinates = $this->getCoordinates($term);
        $result = array();
        if (isset($coordinates['lat']) && isset($coordinates['lng'])) {
            $lat1 = $coordinates['lat'];
            $lng1 = $coordinates['lng'];

            $result = $this->eventMapper->findEventsInRadius(
                    $lat1, $lng1, $distance);
        }
        return empty($result) ? $this->eventMapper->findEventsByTerm($term) : $result;
    }

    /**
     * {@inheritDoc}
     */
    public function createCoordinates(Event $event)
    {
        $address = $event->getAddress();
        $coordinates = $this->getCoordinates($address);
        $event->setCoordinates($coordinates);
        return $event;
    }

    /**
     * {@inheritDoc}
     */
    public function findComment($id)
    {
        return $this->eventMapper->findComment($id);
    }

    /**
     * {@inheritDoc}
     */
    public function sendNotify(Event $event)
    {
        $mail = new Message();
        $mail->setBody(
                '<b>Description: </b>' . $event->getDescription() . '\n' .
                '<b>Address :</b>' . $event->getAddress() . '\n' .
                '<b>Date :</b>' . $event->getFromDate()->format("y-m-d")
        );
        $mail->setFrom($event->getEmail(), $event->getEmail());
        $mail->addTo('admin@domena.pl', 'Administrator');
        $mail->setSubject('New event: ' . $event->getName());

        $transport = new Sendmail();
        $transport->send($mail);
    }

    /**
     * {@inheritDoc}
     */
    public function save($object)
    {
        return $this->eventMapper->save($object);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($object)
    {
        return $this->eventMapper->remove($object);
    }

    /**
     * Zwraca współrzędne dla podanego adresu z wykorzystaniem
     * Google Maps Api
     * 
     * @param string $address
     * @return array $coordinates['lat', 'lng']
     */
    private function getCoordinates($address)
    {
        $url = $this->url . urlencode($address);

        $respJson = $this->curlFileGetContents($url);
        $resp = json_decode($respJson, true);

        if (isset($resp['results'][0]['geometry']['location'])) {
            return $resp['results'][0]['geometry']['location'];
        }
    }

    /**
     * Funkcja pobiera wartości zwracane przez GoogleMapsApi
     * w formacie JSON
     * 
     * @param string $url
     * @return array $contents
     */
    private function curlFileGetContents($url)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $url);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents) {
            return $contents;
        } else {
            return array();
        }
    }

}
