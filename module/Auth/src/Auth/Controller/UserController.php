<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.12.2015
 * Time: 14:48
 */

namespace Auth\Controller;

use Auth\Form\LoginForm;
use Auth\Form\RegistrationForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
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
     * login action
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

            $data = new User();
            $data->exchangeArray($request->getPost());
            $user = $this->getUsersTable()->getUser($data);

          //  if($this->getUsersTable()->verifyUser($user))
            if($user)
            {
                //if user verified, open session
                //echo('verified');die;
                $userSession->userName = $user['name'];
                $userSession->userId = $user['id'];
            }
            else
            {
                //TODO:see what was wrong and return to the log in form
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
            return $this->redirect()->toRoute('album', array('action' => 'index'));
        }
    }

    public function logoutAction()
    {
        $userSession = new Container('user');
        $userSession->getManager()->getStorage()->clear('user');
        // or unset($_SESSION['user']);
        return $this->redirect()->toRoute('user', array('action' => 'index'));
    }

    public function registerAction()
    {
        $userSession = new Container('user');
        if(isset($userSession->userName))
            return $this->redirect()->toRoute('album', array('action' => 'index'));

        $form = new RegistrationForm();

        $request = $this->getRequest();
        if($request->isPost())
        {
            $form->setData($request->getPost());

            if($form->isValid())
            {
                $userName = $request->getPost('userName');
                $password = $request->getPost('userPassword');

                $user = new User();
                $user->exchangeArray($request->getPost());

                try{
                    $this->getUsersTable()->registerUser($user);
                }
                catch(\Exception $e)
                {
                    //TODO: красиво выдать ошибку
                    echo('User name already taken');
                    return array('form' => $form);
                }
            }

            $userSession->userName = $userName;
            $userSession->userPassword = $password;

            return $this->redirect()->toRoute('album', array('action' => 'index'));
        }

        return array('form' => $form);
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