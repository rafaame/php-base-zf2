<?php

namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper,
	Zend\ServiceManager\ServiceLocatorAwareInterface,

	Admin\Model;

class LoggedAdmin extends AbstractHelper implements ServiceLocatorAwareInterface
{

	use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    public function __invoke()
    {

    	$model = new Model\Admin($this->getServiceLocator()->getServiceLocator());

    	return $model->getCurrent();
		
	}
	
}