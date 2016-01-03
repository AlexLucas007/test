<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.12.2015
 * Time: 14:40
 */

namespace Auth\Model;

use Zend\Authentication\AuthenticationService;
use Zend\Validator\Db\RecordExists;
use Zend\Db\Sql\Select as Select;
use Zend\Db\TableGateway\TableGateway;


class UsersTable
{
    protected $tableGateway;

    private $currentUser;

    public function __construct($tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @param User $user
     * @return db adapter
     */
    public function verifyUser(User $user)
    {
        $select = new Select();
        $adapter = $this->tableGateway->getAdapter();
        $select->from('users')
            ->where->equalTo('name', $user->userName)
            ->where->equalTo('pass', $user->password);

        $validator = new RecordExists($select);
        $validator->setAdapter($adapter);

        if ($validator->isValid($user->userName))
        {
            $this->currentUser = $user;
            return true;
        }
        else
            return false;
    }

    public function openUserSession(User $user)
    {
        $auth = new AuthenticationService($this->tableGateway->getAdapter());
        $storage = $auth->getStorage();
        $storage->write(array());
    }

    public function deleteUserSession(User $user)
    {
        unset($this->currentUser);

    }

    public function getUserById($id)
    {

    }

    public function getAllUsers()
    {

    }

    public function deleteUser($id)
    {

    }

    public function registerUser(User $user)
    {

    }

}
