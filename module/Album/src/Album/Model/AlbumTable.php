<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.12.2015
 * Time: 17:37
 */

namespace Album\Model;

use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Auth\Model\User;
use Zend\Session\Container;

class AlbumTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getAlbum($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();

        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveAlbum(Album $album)
    {
        $user = new Container('user');


        $data = array(
            'artist' => $album->artist,
            'title'  => $album->title,
            'userId' => $album->userId,
        );



        //example
        //INSERT INTO albums (artist, title, userId) SELECT 'QWERTY', 'asd ASD asd', users.id FROM users WHERE users.name = 'MishaLucas'

        $id = (int) $album->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getAlbum($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Album id does not exist');
            }
        }
    }

    public function getAlbumTracks($albumId)
    {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = new Select();
        $select->columns(array('name', 'id'), false);
        $select->from('tracks')
                ->where->equalTo('albumId', $albumId);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        return $result;
    }


    public function getUserAlbums(User $user)
    {
        $sql = new Sql($this->tableGateway->getAdapter());

        $select = $sql->select();
        $select->columns(array('id as album_id', 'title', 'artist'));
        $select->from('albums', 'users')
               ->join('users', 'albums.userId = users.id');

        //var_dump($select->getSqlString());die;

        $where = new Where();
        $where->equalTo('users.name', $user->userName);
        $select->where($where);
        //var_dump($select->getSqlString());die;
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        return $result;
    }

    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function getTrack($id)
    {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = new Select();
        $select->from('tracks')
            ->where->equalTo('id', $id);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        return $result->current();
    }


    public function saveTrack(Track $track)
    {
        $data = array(
            'name'  => $track->title,
            'albumId' => $track->albumId,
        );

        //example
        //INSERT INTO albums (artist, title, userId) SELECT 'QWERTY', 'asd ASD asd', users.id FROM users WHERE users.name = 'MishaLucas'

        $id = (int) $track->id;
        if ($id == 0) {
            //$this->tableGateway->insert($data);
            $insert = new Insert('tracks');
            $insert->values($data);
            $sql = new Sql($this->tableGateway->getAdapter());
            $statement = $sql->prepareStatementForSqlObject($insert);
            $statement->execute();
        } else {
            if ($this->getTrack($id)) {
                $this->tracksTableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Album id does not exist');
            }
        }
    }
}