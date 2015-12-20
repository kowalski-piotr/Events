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

use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;
use Events\Entity\Comment;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Zestaw pól formularza reprezentujących obiekt Comment
 */
class CommentFieldset extends Fieldset implements InputFilterProviderInterface
{

    /**
     * @param  null|int|string  $name    Optional name for the element
     * @param  array            $options Optional options for the element
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        // standardowy hydrator Doctrine ORM
        $this->setHydrator(new ClassMethods(false));
        // ustawienie encji Comment jako obiektu formularza
        $this->setObject(new Comment());

        $this->add(array(
            'type' => 'hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Email',
            'name' => 'email',
            'options' => array(
                'label' => 'Email'
            )
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'content',
            'options' => array(
                'label' => 'Description'
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
            'content' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StripTags'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\StringLength',
                        'options' => array(
                            'max' => 600,
                            'min' => 2
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
        );
    }

}
