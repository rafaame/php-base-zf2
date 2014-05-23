<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel,
	Zend\Http\Request as HttpRequest,

	Admin\Form,
	Admin\Model,
	Admin\Entity;

class AdminController extends BaseController
{

	public function indexAction()
	{

		return $this->forward()->dispatch('Admin\Controller\Admin', array('action' => 'list'));

	}

	public function listAction()
    {

    	$serviceLocator = $this->getServiceLocator();
		$objectManager = $this->getObjectManager();

		$model = new Model\Admin($serviceLocator, $objectManager);
		$entities = $model->findAll();
		//$paginator = $model->paginator();

		//if($page = (int) $this->getEvent()->getRouteMatch()->getParam('page'))
		//	$paginator->setCurrentPageNumber($page);

        return new ViewModel
        ([

        	//'paginator' => $paginator,
        	'entities' => $entities,
        	'loggedAdmin' => $model->getCurrent()

        ]);

    }

    public function addAction()
    {

    	$serviceLocator = $this->getServiceLocator();
		$objectManager = $this->getObjectManager();

		$form = new Form\Admin\Add($serviceLocator, $objectManager);

    	if($this->request instanceof HttpRequest && $this->request->isPost())
    	{

    		$data = $this->request->getPost();
    		$form->setData($data);

    		if($form->isValid())
    		{

    			$entity = $form->getData();

    			$model = new Model\Admin($serviceLocator, $objectManager);
				$model->save($entity, true);

		    	if($entity->getId())
		    	{

		    		$this->flashMessenger()->addSuccessMessage(__('Admin successfully added to the database.'));

		    		if(isset($data['redirect-add']))
						return $this->redirect()->toRoute('admin', ['controller' => 'admin', 'action' => 'add']);
					else
						return $this->redirect()->toRoute('admin', ['controller' => 'admin']);

		    	}
		    	else
		    	{

		    		$this->flashMessenger()->addErrorMessage(__('There was an error adding the admin to the database. Contact the administrator.'));

		    	}

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

		$form = new Form\Admin\Edit($serviceLocator, $objectManager);

		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$entity = $objectManager
					->getRepository('Admin\Entity\Admin')
					->findOneBy(['id' => $id]);

		if($entity)
		{

			$form->bind($entity);

	    	if($this->request instanceof HttpRequest && $this->request->isPost())
	    	{

	    		$data = $this->request->getPost()->toArray();
	    		$doPasswordHashing = true;

	    		//We have the special case where we doesn't want to change the password
	    		if(!$data['password'])
	    			$doPasswordHashing = false;

	    		$form->setData($data);

	    		if($form->isValid())
	    		{

					$model = new Model\Admin($serviceLocator, $objectManager);
					$model->save($entity, $doPasswordHashing);

		    		$this->flashMessenger()->addSuccessMessage(__('Admin successfully saved to the database.'));
		    		return $this->redirect()->toRoute('admin', ['controller' => 'admin']);

			    }

	    	}

    	}
    	else
    	{

    		$this->flashMessenger()->addErrorMessage(__('A admin with this ID was not found in the database.'));
   			return $this->redirect()->toRoute('admin', ['controller' => 'admin']);

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

		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$entity = $objectManager
					->getRepository('Admin\Entity\Admin')
					->findOneBy(['id' => $id]);

		if($entity)
		{

			$model = new Model\Admin($serviceLocator, $objectManager);

			if($model->getCurrent()->getId() != $entity->getId())
			{

				$model->remove($entity);

				$this->flashMessenger()->addSuccessMessage(__('Admin successfully deleted from the database.'));

			}
			else
				$this->flashMessenger()->addErrorMessage(__('You cannot delete yourself.'));

		}
		else
			$this->flashMessenger()->addErrorMessage(__('A admin with this ID was not found in the database.'));

		return $this->redirect()->toRoute('admin', ['controller' => 'admin']);

    }

}

