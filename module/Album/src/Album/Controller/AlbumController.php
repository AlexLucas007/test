<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.12.2015
 * Time: 17:22
 */

namespace Album\Controller;

use Album\Form\AddTrackForm;
use Album\Model\Track;
use Auth\Model\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Album\Model\Album;
use Album\Model\AlbumTable;
use Album\Form\AlbumForm;


class AlbumController extends AbstractActionController
{
    protected $albumTable;

    public function indexAction()
    {
        $userSession = new Container('user');

        if(!isset($userSession->userName))
        {
            return $this->redirect()->toRoute('user', array('action' => 'index'));
        }

        $user = new User();
        $user->exchangeArray($userSession);

        return new ViewModel(array(
            //'albums' => $this->getAlbumTable()->fetchAll(),
            'albums' => $this->getAlbumTable()->getUserAlbums($user),
            'userName' => $userSession->userName,
        ));
    }

    public function addAction()
    {
        $userSession = new Container('user');

        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $album = new Album();
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $album->exchangeArray($form->getData());
                $album->userId = $userSession->userId;
                $this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album', array(
                'action' => 'add'
            ));
        }

        // Get the Album with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $album = $this->getAlbumTable()->getAlbum($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('album', array(
                'action' => 'index'
            ));
        }

        $form  = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getAlbumTable()->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return array(
            'id'    => $id,
            'album' => $this->getAlbumTable()->getAlbum($id)
        );
    }
/*******************************    TRACKS  *******************************************/

    public function tracklistAction()
    {
        $userSession = new Container('user');
        if(!isset($userSession))
            return $this->redirect()->toRoute('user', array('action' => 'index'));

        $albumId = (int) $this->params()->fromRoute('id', 0);
        if(!$albumId)
        {
            //TODO:return 'invalid album' message
        }

        return new ViewModel(array(
            //'albums' => $this->getAlbumTable()->fetchAll(),
            'tracks' => $this->getAlbumTable()->getAlbumTracks($albumId),
            'userName' => $userSession->userName,
            'album' => $this->getAlbumTable()->getAlbum($albumId),
        ));
    }

    public function addTrackAction()
    {
        $form = new AddTrackForm();
        $form->get('submit')->setValue('Add');

        $id = (int)$this->params()->fromRoute('id', 0);

        if($id)
            $form->get('albumId')->setValue($id);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $track = new Track();
            $albumId = $request->getPost()->albumId;

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $track->exchangeArray($form->getData());

                $this->getAlbumTable()->saveTrack($track);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album', array('action' => 'tracklist', 'id' => $albumId));
            }
        }
        return array('form' => $form);
    }

    public function deleteTrack()
    {

    }

    public function editTrack()
    {

    }
/****************************************************************************************************/
    public function getAlbumTable()
    {
        if (!$this->albumTable) {
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('Album\Model\AlbumTable');
        }
        return $this->albumTable;
    }
}