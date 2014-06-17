<?php

namespace Admin\Controller;

use Andreatta\Controller\Base,
	Zend\View\Model\ViewModel,
	Zend\Http\Request as HttpRequest,
	Zend\Json\Json,

	Admin\Form,
	Admin\Model,
	Admin\Entity;

class ConfigurationController extends BaseController
{

	public function indexAction()
	{

		return $this->forward()->dispatch('Admin\Controller\Configuration', array('action' => 'list'));

	}

    public function listAction()
    {

    	$serviceLocator = $this->getServiceLocator();
		$objectManager = $this->getObjectManager();

		$model = new Model\Configuration($serviceLocator, $objectManager);
		$entities = $model->findAll();
		//$paginator = $model->paginator();

		//if($page = (int) $this->getEvent()->getRouteMatch()->getParam('page'))
		//	$paginator->setCurrentPageNumber($page);

        return new ViewModel
        ([

        	//'paginator' => $paginator
        	'entities' => $entities,

        ]);

    }

    public function addAction()
    {

    	$serviceLocator = $this->getServiceLocator();
		$objectManager = $this->getObjectManager();

		$form = new Form\Configuration\Add($serviceLocator, $objectManager);

    	if($this->request instanceof HttpRequest && $this->request->isPost())
    	{

    		$data = $this->request->getPost();
    		$form->setData($data);

    		if($form->isValid())
    		{

    			$entity = $form->getData();

    			$model = new Model\Configuration($serviceLocator, $objectManager);
				$model->save($entity);

		    	if($entity->getId())
		    	{

		    		$this->flashMessenger()->addSuccessMessage(__('Configuration successfully added to the database.'));
		    		//@FIXME: verify if all controllers redirects to correct pages after actions

		    		if(isset($data['redirect-add']))
						return $this->redirect()->toRoute('admin', ['controller' => 'configuration', 'action' => 'add']);
					else
						return $this->redirect()->toRoute('admin', ['controller' => 'configuration']);

		    	}
		    	else
		    		$this->flashMessenger()->addErrorMessage(__('There was an error adding the configuration to the database. Contact the administrator.'));

		    }

    	}

    	return new ViewModel
    	([

			'form' => $form,

		]);

    }

    public function editAction()
    {

    	$serviceLocator = $this->getServiceLocator();
		$objectManager = $this->getObjectManager();

		$model = new Model\Configuration($serviceLocator, $objectManager);
		$form = new Form\Configuration\Edit($serviceLocator, $objectManager);

		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$entity = $model->findOneById($id);

		if($entity)
		{

			$form->bind($entity);

	    	if($this->request instanceof HttpRequest && $this->request->isPost())
	    	{

	    		$data = $this->request->getPost();
    			$form->setData($data);

    			//@FIXME: verify if all !$form->isValid() conditions are right
	    		if($form->isValid())
	    		{

					$model->save($entity);

		    		$this->flashMessenger()->addSuccessMessage(__('Configuration successfully saved to the database.'));
		    		return $this->redirect()->toRoute('admin', ['controller' => 'configuration']);

			    }

	    	}

    	}
    	else
    	{

    		$this->flashMessenger()->addErrorMessage(__('A configuration with this ID was not found in the database.'));
    		return $this->redirect()->toRoute('admin', ['controller' => 'configuration']);

    	}

    	return new ViewModel
    	([

			'form' => $form,
			'entity' => $entity,

		]);

    }

    public function deleteAction()
    {

    	$serviceLocator = $this->getServiceLocator();
		$objectManager = $this->getObjectManager();

		$model = new Model\Configuration($serviceLocator, $objectManager);

		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$entity = $model->findOneById($id);

		if($entity)
		{

			$model->remove($entity);

			$this->flashMessenger()->addSuccessMessage(__('Configuration successfully deleted from the database.'));

		}
		else
			$this->flashMessenger()->addErrorMessage(__('A configuration with this ID was not found in the database.'));

		return $this->redirect()->toRoute('admin', ['controller' => 'configuration']);

    }

}

