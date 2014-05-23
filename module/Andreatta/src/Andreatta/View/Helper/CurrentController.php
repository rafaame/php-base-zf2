<?php

namespace Andreatta\View\Helper;

use Zend\Filter\Word\CamelCaseToDash;

class CurrentController extends RouteParam
{
	
	public function __invoke()
	{

		$filter = new CamelCaseToDash();

		$controller = parent::__invoke('controller');
		$controller = substr($controller, strrpos($controller, '\\') + 1);
		$controller = strtolower($filter->filter($controller));

		return $controller;
		
	}

}