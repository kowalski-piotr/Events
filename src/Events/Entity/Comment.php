<?php

/**
 * Zend Framework 2 Events Module
 *
 * @link      http://github.com/pchela/events 
 * @copyright Copyright (c) 20015 Kowalski Piotr (http://www.kowalski-piotr.pl)
 * @license   https://opensource.org/licenses/MIT
 * @since     File available since Release 0.0.1
 */

namespace Events\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Events\Entity\Event;

/**
 * Klasa reprezentująca wydarzenie
 * Encja z adnotacjami Doctrine ORM 
 * 
 * @ORM\Entity 
 */
class Comment
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * Email użytkownika dodającego komentarz
     * 
     * @ORM\Column(type="string", length=50) 
     */
    protected $email;

    /**
     * Zawartość komentarza
     * 
     * @ORM\Column(type="string", length=600) 
     */
    protected $content;

    /**
     * Adres IP użytkownika dodającego komentarz
     * 
     * @ORM\Column(type="string", length=15) 
     */
    protected $ip;

    /**
     * Data stworzenia komentarza
     * 
     * @ORM\Column(type="datetime") 
     */
    protected $createdAt;

    /**
     * Wydarzenie, do którego przypisany jest komentarz
     * 
     * @var Event $events
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="comments", fetch="EAGER")
     * @ORM\JoinColumn(name="eventId", referencedColumnName="id")
     */
    protected $event;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime("now"));
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setEvent(Event $event)
    {
        $this->event = $event;
        return $this;
    }

}
