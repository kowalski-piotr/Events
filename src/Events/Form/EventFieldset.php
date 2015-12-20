<?php

/**
 * Zend Framework 2 Events Module
 *
 * @link      http://github.com/pchela/events 
 * @copyright Copyright (c) 20015 Kowalski Piotr (http://www.kowalski-piotr.pl)
 * @license   https://opensource.org/licenses/MIT
 * @since     File available since Release 0.0.1
 */

namespace Events\Form;

use DateInterval;
use DateTime;
use Events\Entity\Event;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Zestaw pól formularza reprezentujących obiekt Event
 */
class EventFieldset extends Fieldset implements InputFilterProviderInterface
{

    /**
     * @param  null|int|string  $name    Optional name for the element
     * @param  array            $options Optional options for the element
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new Event());

        $this->add(array(
            'type' => 'hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'name',
            'options' => array(
                'label' => 'Name'
            )
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'description',
            'options' => array(
                'label' => 'Description'
            )
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'address',
            'options' => array(
                'label' => 'Address'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Email',
            'name' => 'email',
            'options' => array(
                'label' => 'Email'
            )
        ));

        $this->add(array(
//            'type' => 'Zend\Form\Element\DateTime',
            'type' => 'text',
            'name' => 'fromDate',
            'options' => array(
                'label' => 'From',
                'format' => "Y-m-d H:i:s"
            )
        ));
        $this->add(array(
            'type' => 'text',
//            'type' => 'Zend\Form\Element\DateTime',
            'name' => 'toDate',
            'options' => array(
                'label' => 'To',
                'format' => "Y-m-d H:i:s"
            ),
            'attributes' => array(
                "data-provide" => "datepicker",
                'class' => 'form-control'
            )
        ));
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'name' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                    array('name' => 'Zend\Filter\StripTags'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\StringLength',
                        'options' => array(
                            'max' => 50
                        ),
                    ),
                ),
            ),
            'description' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                    array('name' => 'Zend\Filter\StripTags'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\StringLength',
                        'options' => array(
                            'max' => 300
                        ),
                    ),
                ),
            ),
            'address' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                    array('name' => 'Zend\Filter\StripTags'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\StringLength',
                        'options' => array(
                            'max' => 50
                        ),
                    ),
                ),
            ),
            'email' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                    array('name' => 'Zend\Filter\StripTags'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\EmailAddress',
                    ),
                ),
            ),
            'fromDate' => array(
                'required' => true,
                'filters' => array(
                    // custom filter
                    array(
                        'name' => 'Events\Form\Filter\ConvertToDateTime',
                        'options' => array(
                            'format' => "Y-m-d H:i:s"
                        )
                    ),
                ),
                'validators' => array(
                    // custom validator
                    array(
                        'name' => 'Zend\Validator\Date',
                        'options' => array(
                            'format' => 'Y-m-d H:i:s'
                        )
                    ),
                    array(
                        'name' => 'Events\Form\Validator\DateGreaterThan',
                        'options' => array(
                            'min' => (new DateTime("now"))->add(new DateInterval('P7D')),
                        ),
                    ),
//                    array('name' => 'Events\Form\Validator\isDateOffset'),
//                    array(
//                        'name' => 'Zend\Validator\DateStep',
//                        'options' => array(
//                            'format' => 'Y-m-d H:i:s',
//                            'baseValue' => new DateTime("now"),
//                            'step' => new DateInterval('P7D')
//                        ),
//                    ),
                )
            ),
            'toDate' => array(
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'Events\Form\Filter\ConvertToDateTime',
                        'options' => array(
                            'format' => "Y-m-d H:i:s"
                        )
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\Date',
                        'options' => array(
                            'format' => 'Y-m-d H:i:s'
                        )
                    ),
                    array(
                        'name' => 'Events\Form\Validator\DateGreaterThan',
                        'options' => array(
                            'min' => $this->get('fromDate')->getValue(),
                            'format' => 'Y-m-d H:i'
                        ),
                    ),
                    // custom validator
//                    array('name' => 'Events\Form\Validator\isDateAfter'),
                ),
            ),
        );
    }

}
