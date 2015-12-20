<?php

/**
 * Zend Framework 2 Events Module
 *
 * @link      http://github.com/pchela/events 
 * @copyright Copyright (c) 20015 Kowalski Piotr (http://www.kowalski-piotr.pl)
 * @license   https://opensource.org/licenses/MIT
 * @since     File available since Release 0.0.1
 */

namespace Events\Form\Validator;

use Zend\Validator\AbstractValidator;
use Events\Entity\Event;

/**
 * Walidator sprawdzający poprawność daty rozpoczęcia wydarzenia
 */
class isDateOffset extends AbstractValidator
{

    const OFFSET = 'dateOffset';

    // ustawienie szablonu wiadomości zwracanych przez walidator
    protected $messageTemplates = array(
        self::OFFSET => "Niestety, możesz dodać jedynie wydarzenia, które rozpoczynają się nie wcześniej niż za " . Event::DAY_OFFSET . "dni."
    );

    /**
     * Zwraca true jeżeli wydarzenie rozpoczyna się 
     * nie wcześniej niż za podaną ilość dni
     *
     * @param  \DateTime $date
     * @return bool
     */
    public function isValid($date)
    {
        $this->setValue($date);

        $dayOffset = (int) (new \DateTime("now"))->diff($date)->format('%R%a');
        
        // czy wydarzenie nie rozpoczyna się wcześniej niż za podaną ilość dni ?
        if ($dayOffset < Event::DAY_OFFSET) {
            $this->error(self::OFFSET);
            return false;
        }
        return true;
    }

}
