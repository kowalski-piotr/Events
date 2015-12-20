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
 * Walidator sprawdzający czy data zakończenia wydarzenia 
 * została ustawiona po dacie ropoczęcia
 */
class isDateAfter extends AbstractValidator
{

    const AFTER = 'dateAfter';

    // ustawienie szablonu wiadomości zwracanych przez walidator
    protected $messageTemplates = array(
        self::AFTER => "Wydarzenie nie może zakończyć się przed rozpoczęciem"
    );

    /**
     * Zwraca true jeżeli daty podane w formularzu 
     * spełniają wymagania kolejności
     *
     * @param  \DateTime $date
     * @return bool
     */
    public function isValid($date, $context = null)
    {
        $this->setValue($date);

        // pobranie daty początkowej z kontekstu formularza
        $fromDate = $context['fromDate'];
        if (!isset($fromDate['second'])) {
            $fromDate['second'] = '00';
        }
        
        $fromDate = sprintf('%s-%s-%s %s:%s:%s', $fromDate['year'],
                $fromDate['month'], $fromDate['day'], $fromDate['hour'],
                $fromDate['minute'], $fromDate['second']);

        $fromDate = new \DateTime($fromDate);

        // porównanie dat
        if ($date < $fromDate) {
            $this->error(self::AFTER);
            return false;
        }

        return true;
    }

}
