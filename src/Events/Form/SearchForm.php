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

use Zend\Form\Form;
use \Zend\InputFilter\InputFilterProviderInterface;

/**
 * Formularz wyszukiwania wydarzeń
 */
class SearchForm extends Form implements InputFilterProviderInterface
{

    /**
     * @param  null|int|string  $name    Optional name for the element
     * @param  array            $options Optional options for the element
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setAttributes(array(
            "role" => "search",
            "class" => "navbar-form"
        ));

        $this->add(array(
            'name' => 'search',
            'type' => 'text',
            'attributes' => array(
                'class' => "form-control",
                'placeholder' => "Szukaj wydarzeń",
                'autocomplete' => "off",
                'autofocus' => "autofocus",
            ),
        ));

        $this->add(array(
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Szukaj',
                'class' => "btn btn-default"
            ),
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
            'search' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                    array('name' => 'Zend\Filter\StripTags')
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\StringLength',
                        'options' => array(
                            'min' => 3
                        ),
                    ),
                ),
            ),
        );
    }

}
