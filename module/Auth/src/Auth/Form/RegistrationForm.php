<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.12.2015
 * Time: 14:26
 */

namespace Auth\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class RegistrationForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'userName',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));
        $this->add(array(
            'name' => 'userPassword',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));

        $this->add(array(
            'name' => 'confirmPassword',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Confirm password',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Register',
                'id' => 'submitButton',
            ),
        ));

        //define input filter
        $inputFilter = new InputFilter();

        $inputFilter->add(array(
            'name' => 'userName',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators'  => array(
                             array(
                'name'    => 'StringLength',
                'options' => array(
                    'encoding' => 'UTF-8',
                    'min'      => 6,
                    'max'      => 18,
                )
                ),)
            ));

        $inputFilter->add(array(
            'name' => 'userPassword',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                            array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 6,
                        'max'      => 18,
                    )
                    )
            )
        )
        );

        $inputFilter->add(array(
            'name' => 'confirmPassword',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                    array('name' => 'Identical',
                          'options' => array(
                            'token' => 'userPassword',
                        )

            )
        )));

        $this->setInputFilter($inputFilter);
    }
}