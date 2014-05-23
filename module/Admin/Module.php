<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Zend\Mvc\ModuleRouteListener,
	Zend\Mvc\MvcEvent,
	Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage,
    Zend\Console\Adapter\AdapterInterface as Console,
    Zend\Http\Request as HttpRequest,

    Admin\Model\Admin;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
				
		//It should be executed with priority higher than 100 (in order to be executed before the checkAcl event)
        $eventManager->attach('dispatch', array($this, 'setAclRole'), 101);

    }

    public function setAclRole($e)
    {

        $routeMatch = $e->getRouteMatch();

        if($routeMatch->getParam('module') != __NAMESPACE__)
            return;

        $model = new Model\Admin($e->getApplication()->getServiceManager());

        if($logged = $model->getCurrent())
            $e->getViewModel()->aclRole = $logged->getRole();
        else
            $e->getViewModel()->aclRole = 'guest';

    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {

        return
        [

            'Zend\Loader\StandardAutoloader' =>
            [

                'namespaces' =>
                [

                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,

                ],

            ],
            
        ];

    }
	
	public function getServiceConfig()
    {

    	return 
		[
    		'factories' => 
			[
				
    			
    			
    		],
					
    	];

    }

    public function getConsoleUsage(Console $console)
    {

        return 
        [

                // Describe available commands
                //'admin email-campaign dispatch-step <step-size>' => 'Step the email campaigns dispatch by sending emails',
     
        ];

    }

}
