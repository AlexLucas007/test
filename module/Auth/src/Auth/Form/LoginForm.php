<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.12.2015
 * Time: 14:26
 */

namespace Auth\Form;

use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('user');
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
       /* $this->add(array(
            'name' => 'rememberme',
            'type' => 'checkbox', // 'Zend\Form\Element\Checkbox',
//            'attributes' => array( // Is not working this way
//                'type'  => '\Zend\Form\Element\Checkbox',
//            ),
            'options' => array(
                'label' => 'Remember Me?',
//				'checked_value' => 'true', without value here will be 1
//				'unchecked_value' => 'false', // witll be 1
            ),
        ));*/
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Login',
                'id' => 'submitButton',
            ),
        ));
    }
}