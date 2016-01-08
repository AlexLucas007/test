<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.01.2016
 * Time: 12:15
 */

/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.12.2015
 * Time: 17:29
 */

namespace Album\Model;


class Track
{
    public $id;
    public $title;
    public $albumId;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->title  = (!empty($data['title'])) ? $data['title'] : null;
        $this->albumId = (!empty($data['albumId'])) ? (int)$data['albumId'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}