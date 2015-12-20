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

use DateTime;
use Zend\Filter\DateTimeFormatter;

/**
 * Events\Form\Filter\ConvertToDateTime
 * 
 * Filtr konwertujący format daty zwracany przez element 
 * Zend\Form\Element\DateTimeSelect na format DateTime 
 */
class ConvertToDateTime extends DateTimeFormatter
{

    /**
     * Normalize the provided value to a formatted string
     *
     * @param  string|int|DateTime $value
     * @return string
     */
    protected function normalizeDateTime($value)
    {
        if ($value === '' || $value === null) {
            return $value;
        }

        if (!is_string($value) && !is_int($value) && !$value instanceof DateTime) {
            return $value;
        }

        if (is_int($value)) {
            //timestamp
            $value = new DateTime('@' . $value);
        } elseif (!$value instanceof DateTime) {
            $value = new DateTime($value);
        }

        return $value;
    }

}
