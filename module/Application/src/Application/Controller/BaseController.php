<?php

namespace Application\Controller;

use Andreatta\Controller\Base,
	Zend\Mvc\MvcEvent,
		
	Application\Model;


abstract class BaseController extends Base
{

	public function onDispatch(MvcEvent $e)
	{

		parent::onDispatch($e);

	}

}
