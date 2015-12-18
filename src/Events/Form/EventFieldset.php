<?php

/*
 * The MIT License
 *
 * Copyright 2015 pchel.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Events\Form;

use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;
use Events\Entity\Event;
use Zend\InputFilter\InputFilterProviderInterface;

class EventFieldset extends Fieldset implements InputFilterProviderInterface
{

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
            'type' => 'Zend\Form\Element\DateTimeSelect',
            'name' => 'fromDate',
            'options' => array(
                'label' => 'From',
                'format' => "Y-m-d H:i:s"
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\DateTimeSelect',
            'name' => 'toDate',
            'options' => array(
                'label' => 'To',
                'format' => "Y-m-d H:i:s"
            )
        ));
    }

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
//                    array('name' => 'Zend\Filter\StringTrim'),
////                    array('name' => 'Zend\Filter\StripTags'),
                ),
            ),
            'toDate' => array(
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'callback' => function ($date)
                            {
                                // Convert the date to a specific format
                                if (is_array($date))
                                {
                                    if (!isset($date['second']))
                                    {
                                        $date['second'] = '00';
                                    }
                                    $date = sprintf('%s-%s-%s %s:%s:%s', 
                                            $date['year'], $date['month'], $date['day'], 
                                            $date['hour'], $date['minute'], $date['second']
                                    );
                                }

                                return $date;
                            }
                        )
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\Callback',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\Callback::INVALID_VALUE => 'The departure time is less than the arrival time',
                            ),
                            'callback' => function($value, $context = array())
                    {
                        // value of this input
                        $toDate = $value;
                        // value of input to check against from context
                        $fromDate = $context['fromDate'];
                        // compare times, eg..
                        $isValid = $toDate >= $fromDate;
                        $fromDate = \DateTime::createFromFormat(
                                        'Y-m-d', $fromDate['year'] . '-' .
                                        $fromDate['month'] . '-' .
                                        $fromDate['day']
                        );

                        $dayOffset = (int) (new \DateTime("now"))->diff($fromDate)->format('%R%a');
                        if ((int) $dayOffset > Event::DAY_OFFSET)
                        {
                            $isValid = false;
                        }

                        return $isValid;
                    },
                        ),
                    ),
                ),
            ),
        );
    }

}
