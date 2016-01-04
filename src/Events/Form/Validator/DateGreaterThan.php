<?php

/**
 * Zend Framework 2 Events Module
 *
 * Long description for file (if any)...
 *
 * @link      http://github.com/pchela/events 
 * @copyright Copyright (c) 20015 Kowalski Piotr (http://www.kowalski-piotr.pl)
 * @license   https://opensource.org/licenses/MIT
 * @since     File available since Release 0.0.1
 */

namespace Events\Form\Validator;

use DateTime;
use Zend\Validator\GreaterThan;

/**
 * Events\Form\Validator\DateGreaterThan
 * 
 * Walidator sprawdzający czy data jest większa (bądź równa) 
 * od drugiej podanej daty (opcja min)
 *
 */
class DateGreaterThan extends GreaterThan
{

    /**
     * Validity constants
     * @var string
     */
    const INVALID = 'dateInvalid';
    const INVALID_DATE = 'dateInvalidDate';
    const FALSEFORMAT = 'dateFalseFormat';
    const NOT_GREATER = 'notGreaterThan';
    const NOT_GREATER_INCLUSIVE = 'notGreaterThanInclusive';

    /**
     * Default format constant
     * @var string
     */
    const FORMAT_DEFAULT = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    protected $format = self::FORMAT_DEFAULT;

    /**
     * @var string
     */
    protected $stringMin;

    /**
     * @var array
     */
    protected $messageVariables = array(
        'format' => 'format',
        'min' => 'stringMin'
    );

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID => "Invalid type given. String, integer, array or DateTime expected",
        self::INVALID_DATE => "The input does not appear to be a valid date",
        self::FALSEFORMAT => "The input does not fit the date format '%format%'",
        self::NOT_GREATER => "The input is not greater than '%min%'",
        self::NOT_GREATER_INCLUSIVE => "The input is not greater or equal than '%min%'"
    );

    /**
     * Sets the min option
     *
     * @param  mixed $min
     * @return GreaterThan Provides a fluent interface
     */
    public function setMin($min)
    {
        $this->min = $this->convertToDateTime($min);
        $this->setStringMin($min);
        return $this;
    }

    /**
     * Sets the format option
     *
     * Format cannot be null.  It will always default to 'Y-m-d', even
     * if null is provided.
     *
     * @param  string $format
     * @return Date provides a fluent interface
     * @todo   validate the format
     */
    public function setFormat($format = self::FORMAT_DEFAULT)
    {
        $this->format = (empty($format)) ? self::FORMAT_DEFAULT : $format;
        return $this;
    }

    public function setStringMin($min)
    {
        if ($min instanceof DateTime) {
            $this->stringMin = $min->format($this->format);
        } else {
            $this->stringMin = $min;
        }
    }

    /**
     * Returns true if and only if $value is greater than min option
     *
     * @param  mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $value = $this->convertToDateTime($value);
        $this->setValue($value);

        if ($this->inclusive) {
            if ($this->min > $value) {
                $this->error(self::NOT_GREATER_INCLUSIVE);
                return false;
            }
        } else {
            if ($this->min >= $value) {
                $this->error(self::NOT_GREATER);
                return false;
            }
        }

        return true;
    }

    /**
     * Attempts to convert an int, string, or array to a DateTime object
     *
     * @param  string|int|array $param
     * @param  bool             $addErrors
     * @return bool|DateTime
     */
    protected function convertToDateTime($param, $addErrors = true)
    {
        if ($param instanceof DateTime) {
            return $param;
        }

        $type = gettype($param);
        if (!in_array($type, array('string', 'integer', 'array'))) {
            if ($addErrors) {
                $this->error(self::INVALID);
            }
            return false;
        }

        $convertMethod = 'convert' . ucfirst($type);
        return $this->{$convertMethod}($param, $addErrors);
    }

    /**
     * Attempts to convert an integer into a DateTime object
     *
     * @param  integer $value
     * @return bool|DateTime
     */
    protected function convertInteger($value)
    {
        return date_create("@$value");
    }

    /**
     * Attempts to convert a string into a DateTime object
     *
     * @param  string $value
     * @param  bool   $addErrors
     * @return bool|DateTime
     */
    protected function convertString($value, $addErrors = true)
    {
        $date = DateTime::createFromFormat($this->format, $value);

        // Invalid dates can show up as warnings (ie. "2007-02-99")
        // and still return a DateTime object.
        $errors = DateTime::getLastErrors();
        if ($errors['warning_count'] > 0) {
            if ($addErrors) {
                $this->error(self::FALSEFORMAT);
            }
            return false;
        }

        return new DateTime($value);
    }

    /**
     * Implodes the array into a string and proxies to {@link convertString()}.
     *
     * @param  array $value
     * @param  bool  $addErrors
     * @return bool|DateTime
     * @todo   enhance the implosion
     */
    protected function convertArray(array $value, $addErrors = true)
    {
        return $this->convertString(implode('-', $value), $addErrors);
    }

}
