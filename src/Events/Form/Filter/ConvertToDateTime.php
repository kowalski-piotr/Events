<?php

/**
 * Zend Framework 2 Events Module
 *
 * @link      http://github.com/pchela/events 
 * @copyright Copyright (c) 20015 Kowalski Piotr (http://www.kowalski-piotr.pl)
 * @license   https://opensource.org/licenses/MIT
 * @since     File available since Release 0.0.1
 */

namespace Events\Form\Filter;

use Zend\Filter\FilterInterface;

/**
 * Events\Form\Filter\ConvertToDateTime
 * 
 * Filtr konwertujący format daty zwracany przez element 
 * Zend\Form\Element\DateTimeSelect na format DateTime 
 */
class ConvertToDateTime implements FilterInterface
{

    /**
     * Zwraca wynik filtrowania podanej wartości
     * 
     * @param array $date
     * @return \DateTime 
     */
    public function filter($date)
    {
        if (!isset($date['second'])) {
            $date['second'] = '00';
        }

        $date = sprintf('%s-%s-%s %s:%s:%s', $date['year'], $date['month'],
                $date['day'], $date['hour'], $date['minute'], $date['second']);


        return \DateTime::createFromFormat('Y-m-d H:i:s', $date);
    }

}
