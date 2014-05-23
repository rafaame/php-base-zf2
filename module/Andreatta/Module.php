<?php
namespace Andreatta;

use Zend\Loader\AutoloaderFactory,
    Zend\Loader\StandardAutoloader,
    Zend\Mvc\MvcEvent,
    Zend\Session;

class Module
{
	
    /**
     * {@inheritDoc}
     */
    public function onBootstrap(MvcEvent $e)
    {

        $application = $e->getParam('application');

        //Set a higher priority, so it gets executed before the matched action
        $application->getEventManager()->attach('dispatch', array($this, 'checkAcl'), 100);

        $application->getEventManager()->attach('dispatch', array($this, 'setModuleLayout'));

        $this->initPhpConfigs($e);
        $this->initAcl($e);

	}

    public function initPhpConfigs($e)
    {

        $application = $e->getParam('application');
        $config = $application->getServiceManager()->get('config');
        $phpSettings = $config['php_settings'];

        if($phpSettings)
            foreach($phpSettings as $key => $value)
                ini_set($key, $value);

    }

    public function initAcl($e)
    {

        $application = $e->getParam('application');
        $config = $application->getServiceManager()->get('config');
        $aclSettings = $config['acl'];

        $acl = new \Zend\Permissions\Acl\Acl();

        foreach($aclSettings as $module => $moduleAcl)
        {

            $roles = $moduleAcl['roles'];
            $resources = $moduleAcl['resources'];

            foreach($roles as $role => $parent)
            {

                $role = implode('.', [$module, $role]);

                if($parent !== null)
                    $parent = implode('.', [$module, $parent]);

                $role = new \Zend\Permissions\Acl\Role\GenericRole($role);
                $acl->addRole($role, $parent);

            }
            
            foreach($resources as $resourceController => $resourceConfig)
            {

                if(is_array($resourceConfig))
                {

                    foreach($resourceConfig as $resource => $role)
                    {

                        $role = implode('.', [$module, $role]);
                        $resource = implode('.', [$module, $resourceController, $resource]);

                        $acl->addResource($resource);
                        $acl->allow($role, $resource);

                    }

                }
                else
                {

                    $role = implode('.', [$module, $resourceConfig]);
                    $resource = implode('.', [$module, $resourceController, '*']);

                    $acl->addResource($resource);
                    $acl->allow($role, $resource);

                }

            }

        }

        $e->getViewModel()->acl = $acl;

    }

    public function checkAcl($e)
    {

        $application = $e->getParam('application');
        $config = $application->getServiceManager()->get('config');

        $request = $application->getServiceManager()->get('Request');
        if(!$request instanceof \Zend\Http\Request)
            return;

        $aclSettings = $config['acl'];

        $matches = $e->getRouteMatch();
        $module = $matches->getParam('module');

        if(!isset($aclSettings[$module]))
            return;

        $controller = explode('\\', $matches->getParam('controller'));
        $controller = array_pop($controller);
        $action = $matches->getParam('action');
        $errorUrl = $aclSettings[$module]['error'];

        $role = implode('.', [$module, $e->getViewModel()->aclRole]);

        $allow = false;

        if($e->getViewModel()->acl->hasResource(implode('.', [$module, $controller, $action])))
            $allow = $e->getViewModel()->acl->isAllowed($role, implode('.', [$module, $controller, $action]));
        else if($e->getViewModel()->acl->hasResource(implode('.', [$module, $controller, '*'])))
            $allow = $e->getViewModel()->acl->isAllowed($role, implode('.', [$module, $controller, '*']));
        else if($e->getViewModel()->acl->hasResource(implode('.', [$module, '*', '*'])))
            $allow = $e->getViewModel()->acl->isAllowed($role, implode('.', [$module, '*', '*']));
        else
            throw new \Exception('No resource ' . implode('.', [$module, $controller, $action]) . ' found.');

        if(!$allow)
        {

            $session = new Session\Container($module);
            $session->previousUrl = $e->getRequest()->getUri()->toString();

            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $e->getRequest()->getBaseUrl() . $errorUrl);
            $response->setStatusCode(302);

            return $response;

        }

    }

    public function setModuleLayout($e)
    {

        $application = $e->getParam('application');

        $request = $application->getServiceManager()->get('Request');
        if(!$request instanceof \Zend\Http\Request)
            return;
        
        $config = $application->getServiceManager()->get('config');
        $layouts = $config['view_manager']['layouts'];
        $matches = $e->getRouteMatch();
        $module = $matches->getParam('module');

        if(!isset($layouts[$module]))
            return;

        $controller = explode('\\', $matches->getParam('controller'));
        $controller = array_pop($controller);
        $action = $matches->getParam('action');
        $layout = null;

        foreach($layouts as $layoutModule => $moduleLayouts)
        {

            if($module == $layoutModule || $layoutModule == '*')
            {

                if(is_array($moduleLayouts))
                {

                    foreach($moduleLayouts as $layoutController => $controllerLayouts)
                    {

                        if($controller == $layoutController || $layoutController == '*')
                        {

                            if(is_array($controllerLayouts))
                            {

                                foreach($controllerLayouts as $layoutAction => $actionLayout)
                                {

                                    if($action == $layoutAction || $layoutAction == '*')
                                    {

                                        $layout = $actionLayout;
                                        break;

                                    }

                                }

                                if($layout)
                                    break;

                            }
                            else
                            {

                                $layout = $controllerLayouts;
                                break;

                            }

                        }


                    }

                    if($layout)
                        break;

                }
                else
                {

                    $layout = $moduleLayouts;
                    break;

                }

            }

        }

        $controller = $e->getTarget();
        $controller->layout($layout);

    }
	
	public function getViewHelperConfig()
    {
		
        return array
		(
			
            'factories' => array
			(
				
                'routeParam' => function($sm) 
				{
			
					$routeMatch = $sm->getServiceLocator()->get('application')->getMvcEvent()->getRouteMatch();
			
                    return new View\Helper\RouteParam($routeMatch);
					
                },
						
				'currentController' => function($sm) 
				{
			
					$routeMatch = $sm->getServiceLocator()->get('application')->getMvcEvent()->getRouteMatch();
			
                    return new View\Helper\CurrentController($routeMatch);
					
                },
						
				'currentAction' => function($sm) 
				{
			
					$routeMatch = $sm->getServiceLocator()->get('application')->getMvcEvent()->getRouteMatch();
			
                    return new View\Helper\CurrentAction($routeMatch);
					
                }
				
            )
				
        );   
   }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	
	public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
	
}