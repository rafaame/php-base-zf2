<?php

namespace Admin\Model;

use Admin\Entity,
	Andreatta\Model\Base as Base;

class Admin extends Base
{
	
	/**
	 * 
	 * @return Zend\Authentication\AuthenticationService
	 */
	protected function getAuthenticationService()
	{
		
		$serviceLocator = $this->getServiceLocator();
		
		return $serviceLocator->get('Admin\Auth');
		
	}
	
	/**
	 * 
	 * @return Zend\Authentication\Adapter\AdapterInterface
	 */
	protected function getAuthenticationAdapter()
	{
		
		return $this->getAuthenticationService()->getAdapter();
		
	}

	public function isLogged()
	{

		$authService = $this->getAuthenticationService();
		
    	return $authService->hasIdentity();

	}
	
	public function getCurrent()
	{
		$authService = $this->getAuthenticationService();
		
    	if ($authService->hasIdentity())
			return $authService->getIdentity();
		
		return null;
	}
	
	public function authenticate($identity, $credential)
	{
		
		$authService = $this->getAuthenticationService();
		$authAdapter = $this->getAuthenticationAdapter();
		
		$authAdapter->setIdentityValue($identity);
		$authAdapter->setCredentialValue($credential);
		$result = $authService->authenticate();
				
		return $result->isValid();
		
	}
	
	public function logout()
	{
		
		$authService = $this->getAuthenticationService();
		
		if($authService->hasIdentity())
		{
			
			$authService->clearIdentity();
			
			return true;
			
		}
		
		return false;
		
	}

	public function paginator($countPerPage = 20, $entityName = 'Admin\Entity\Admin')
	{

		return parent::paginator($countPerPage, $entityName);

	}

	public function save($admin, $doPasswordHashing = false, $flush = true)
	{

		//The form hydrator previously set the password but did not hash it
		if($doPasswordHashing)
			$admin->setPassword($admin->getPassword(), true);

		parent::save($admin, $flush);

	}
	
}