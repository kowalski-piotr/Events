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

use Events\Entity\Comment;
use Events\Entity\Event;
use Events\Mapper\EventMapperInterface;
use InvalidArgumentException;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Stdlib\ArrayObject;
use Zend\Validator\EmailAddress;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

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
     * Zwraca wydarzenie o podanym ID
     * 
     * @param int $id
     * @return Event $event 
     */
    public function getEvent($id)
    {
        return $this->eventMapper->findEvent($id);
    }

    /**
     * Zwraca wszystkie wydarzenia
     * 
     * @return ArrayObject $events
     */
    public function getAllEvents()
    {
        return $this->eventMapper->findAllEvents();
    }

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
    public function searchEvents($term, $distance = 2)
    {
        $coordinates = $this->getCoordinates($term);
        $result = array();
        if (isset($coordinates['lat']) && isset($coordinates['lng'])) {
            $lat1 = $coordinates['lat'];
            $lng1 = $coordinates['lng'];

            $result = $this->eventMapper->findEventsInRadius(
                    $lat1, $lng1, $offset, $limit, $distance);
        }

        if (empty($result)) {
            return $this->eventMapper->findEventsByTerm($term, $offset, $limit);
        }

        return $result;
    }

    /**
     * Wyszukuje współrzędne dla podanego adresu z wykorzystaniem
     * Google Maps Api i wstawia je do Wydarzenia 
     * 
     * @param Event $event
     * @return Event $event
     */
    public function createCoordinates(Event $event)
    {
        $address = $event->getAddress();
        $coordinates = $this->getCoordinates($address);
        $event->setCoordinates($coordinates);
        return $event;
    }

    /**
     * Zwraca komentarz do wydarzenia po ID
     * 
     * @param type $id
     * @return Comment $commentt 
     */
    public function getComment($id)
    {
        return $this->eventMapper->findComment($id);
    }

    /**
     * Wysyła wiadomość pod podany adres email ze szczegółami wydarzenia
     * 
     * @param Event $event
     */
    public function sendNotify(Event $event, $recipient)
    {

        $validator = new EmailAddress();
        if ($validator->isValid($recipient)) {
            // email appears to be valid
        } else {
            // email is invalid; print the reasons
            throw new InvalidArgumentException('Nieprawidłowy format adresu e-mail');
        }

        $mail = new Message();
        $mail->setFrom($event->getEmail(), $event->getEmail());
        $mail->addTo('admin@domena.pl', 'Administrator');
        $mail->setSubject('Dodano nowe wydarzenie: ' . $event->getName());
        $mail->setBody(
                '<b>Nazwa: </b>' . $event->getName() . '\n' .
                '<b>Opis: </b>' . $event->getDescription() . '\n' .
                '<b>Adres :</b>' . $event->getAddress() . '\n' .
                '<b>Data rozpoczęcia :</b>' . $event->getFromDate()->format("y-m-d H:i:s") . '\n' .
                '<b>Data zakończenia :</b>' . $event->getFromDate()->format("y-m-d H:i:s") . '\n'
        );

        $transport = new Sendmail();
        $transport->send($mail);
    }

    /**
     * Zapisuje nowy lub zmieniony obiekt
     * 
     * @param object $entity
     * @return object $entity
     */
    public function save($object)
    {
        return $this->eventMapper->save($object);
    }

    /**
     * Usuwa obiekt
     * 
     * @param object $entity
     * @return void
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

        $coordinates = array();
        $respJson = $this->curlFileGetContents($url);
        $resp = json_decode($respJson, true);

        if (isset($resp['results'][0]['geometry']['location'])) {
            $coordinates = $resp['results'][0]['geometry']['location'];
        }

        return $coordinates;
    }

    /**
     * Funkcja pobiera wartości zwracane przez GoogleMapsApi
     * w formacie JSON
     * 
     * @param string $url
     * @return json $contents
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
            return '';
        }
    }

}
