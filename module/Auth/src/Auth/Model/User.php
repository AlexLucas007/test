<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.12.2015
 * Time: 14:40
 */

namespace Auth\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User implements InputFilterAwareInterface
{
    public $userName;
    public $password;
    public $salt;
    public $id;
    private $inputFilter;


    public function getInputFilter()
    {
        // TODO: Implement getInputFilter() method.
        return;
    /*    if(!$this->inputFilter)
        {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                //'name' =>

            )

            );

        }*/

    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function exchangeArray($data)
    {
        $this->userName = (!empty($data['userName'])) ? $data['userName'] : null;
        $this->password = (!empty($data['userPassword'])) ? $data['userPassword'] : null;
        //$this->id       = (!empty($data['id'])) ? $data['id'] : null;
        //$this->title  = (!empty($data['salt'])) ? $data['salt'] : null;
    }

}