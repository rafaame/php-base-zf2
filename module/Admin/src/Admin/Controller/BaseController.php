<?php

namespace Admin\Controller;

use Andreatta\Controller\Base,
	Zend\Mvc\MvcEvent,
		
	Admin\Model;


class BaseController extends Base
{

	public function onDispatch(MvcEvent $e)
	{

		parent::onDispatch($e);

	}

}
