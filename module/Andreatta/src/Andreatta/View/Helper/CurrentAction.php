<?php

namespace Andreatta\View\Helper;

class CurrentAction extends RouteParam
{
	
	public function __invoke()
	{
		
		return parent::__invoke('action');
		
	}

}