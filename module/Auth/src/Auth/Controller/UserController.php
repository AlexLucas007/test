<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.12.2015
 * Time: 14:48
 */

namespace Auth\Controller;

use Auth\Form\LoginForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Album;
use Album\Model\AlbumTable;
use Album\Form\AlbumForm;
use Auth\Model\User;
use Zend\Session\SessionManager;
use Zend\Authentication\AuthenticationService;
use Zend\Session\Container;
use Zend\Session\Storage\SessionStorage;

class UserController extends AbstractActionController
{
    protected $usersTable;

    protected $sessionStorage;

    /**
     * user action
     */
    public function indexAction()
    {

        $form = new LoginForm();

        $userSession = new Container('user');

        $request = $this->getRequest();
        if($request->isPost())
        {
            //TODO: user authentication
            $userName = $request->getPost('userName');
            $password = $request->getPost('userPassword');

            $user = new User();
            $user->exchangeArray($request->getPost());

            $dbAdapter = $this->getUsersTable()->verifyUser($user);

            if(!is_null($dbAdapter))
            {
                //if user verified, open session
                //echo('verified');die;
                $userSession->userName = $userName;

                //and go to the user's album list

            }
            else
            {
                //see what was wrong and return to the log in form
                //with a corresponding error message
                echo('not verified'); die;

            }

        }

        if(!isset($userSession->userName))
        {
            return array('form' => $form);
        }
        else
        {
            //die('user logged in'.$userSession->userName);
            return $this->redirect()->toRoute('album', array('action' => 'index'));

            //echo("Welcome {$userSession->userName}");
        }


    }


    public function logoutAction()
    {

    }

    public function registerAction()
    {

    }

    public function getUsersTable()
    {
        if(!$this->usersTable)
        {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('Auth\Model\UsersTable');
        }

        return $this->usersTable;
    }


}