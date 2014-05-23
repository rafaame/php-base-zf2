<?php

namespace Andreatta\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RouteParam extends AbstractHelper
{
	
	static protected $routeMatch;
	
	public function __construct($routeMatch = null)
	{
		
		if($routeMatch)
			self::$routeMatch = $routeMatch;
		
	}

	public function __invoke($param)
	{
		
		return self::$routeMatch->getParam($param);
		
	}

}