<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel,
	Zend\Mvc\MvcEvent as MvcEvent,
	Zend\Http\Request as HttpRequest,
		
	Admin\Model,
	Admin\Form,
	Admin\Entity;


class AuthController extends BaseController
{

    public function indexAction()
    {

        return new ViewModel();

    }
	
	public function loginAction()
	{

		$serviceLocator = $this->getServiceLocator();
		$objectManager = $this->getObjectManager();
		
		$form = new Form\Auth\Login($serviceLocator, $objectManager);
				
		if ($this->request instanceof HttpRequest && $this->request->isPost()) 
		{
			
			$form->setData($this->request->getPost());
			
			if ($form->isValid())
			{
				
				$model = new Model\Admin($serviceLocator, $objectManager);
				$entity = $form->getData();
				
				$success = $model->authenticate($entity->getEmail(), $entity->getPassword());
				
				if($success)
				{
					
					$this->flashMessenger()->addSuccessMessage(__('You are logged in!'));
					return $this->redirect()->toRoute('admin');
					
				}
				else
				{

					$this->flashMessenger()->addErrorMessage(__('You are not logged in!'));
					
				}
				
			}
			
		}
		
		return new ViewModel(['form' => $form]);
		
	}
	
	public function logoutAction()
	{

		$serviceLocator = $this->getServiceLocator();
		$objectManager = $this->getObjectManager();
		
		$model = new Model\Admin($serviceLocator, $objectManager);
		$model->logout();
		
		return $this->redirect()->toRoute('admin');
		
	}
}
