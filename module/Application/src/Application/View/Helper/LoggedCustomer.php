<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper,
	Zend\ServiceManager\ServiceLocatorAwareInterface,

	Application\Model;

class LoggedCustomer extends AbstractHelper implements ServiceLocatorAwareInterface
{

	use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    public function __invoke()
    {

    	$model = new Model\Customer($this->getServiceLocator()->getServiceLocator());

    	return $model->getCurrent();
		
	}
	
}