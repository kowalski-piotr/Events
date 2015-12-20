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
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Klasa reprezentująca wydarzenie
 * Encja z adnotacjami Doctrine ORM 
 * 
 * @ORM\Entity 
 */
class Event
{

    /**
     * Nowe wydarzenie musi rozpoczynać się przynajmniej 
     * za @DAY_OFFSET dni od daty dodawania wydarzenia
     * 
     * @const DAY_OFFSET
     */
    const DAY_OFFSET = 7;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * Nazwa wydarzenia
     * 
     * @ORM\Column(type="string", length=50) 
     */
    protected $name;

    /**
     * Opis wydarzenia
     * 
     * @ORM\Column(type="string") 
     */
    protected $description;

    /**
     * Adres wydarzenia
     * 
     * @ORM\Column(type="string", length=300) 
     */
    protected $address;

    /**
     * Email użytkownika dodającego wydarzenia
     * 
     * @ORM\Column(type="string", length=50) 
     */
    protected $email;

    /**
     * Data rozpoczęcia wydarzenia
     * 
     * @ORM\Column(type="datetime") 
     */
    protected $fromDate;

    /**
     * Data zakończenia wydarzenia
     * 
     * @ORM\Column(type="datetime") 
     */
    protected $toDate;

    /**
     * Szerkość geograficzna 
     * 
     * @ORM\Column(type="string", length=15, nullable=true) 
     */
    protected $lat;

    /**
     * Długość geograficzna
     * 
     * @ORM\Column(type="string", length=15, nullable=true) 
     */
    protected $lng;

    /**
     * Komentarze do wydarzenia
     * 
     * @var ArrayCollection $comments
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="event", cascade={"ALL"})
     */
    protected $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFromDate()
    {
        return $this->fromDate;
    }

    public function getToDate()
    {
        return $this->toDate;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setFromDate($fromDate)
    {
//        if (is_string($fromDate)) {
//            $fromDate = new \DateTime($fromDate);
//        }
        $this->fromDate = $fromDate;
        return $this;
    }

    public function setToDate($toDate)
    {
//        if (is_string($toDate)) {
//            $toDate = new \DateTime($toDate);
//        }
        $this->toDate = $toDate;
        return $this;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function setLat($lat)
    {
        $this->lat = $lat;
        return $this;
    }

    public function setLng($lng)
    {
        $this->lng = $lng;
        return $this;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    public function addComments(Collection $collection)
    {
        foreach ($collection as $item) {
            $item->setEvent($this);
            $this->comments->add($item);
        }
    }

    public function addComment(Comment $comment)
    {
        $comment->setEvent($this);
        $this->comments->add($comment);
    }

    public function removeComments(Collection $collection)
    {
        foreach ($collection as $item) {
            $item->setEvent(null);
            $this->comments->removeElement($item);
        }
    }

    public function setCoordinates(array $coordinates)
    {
        if (isset($coordinates['lat'])) {
            $this->setLat($coordinates['lat']);
        }
        
        if (isset($coordinates['lng'])) {
            $this->setLng($coordinates['lng']);
        }
    }

}
