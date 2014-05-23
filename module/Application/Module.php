<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener,
    Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {

        $eventManager = $e->getApplication()->getEventManager();

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        //It should be executed with priority higher than 100 (in order to be executed before the checkAcl event)
        $eventManager->attach('dispatch', array($this, 'setAclRole'), 101);

        $this->setDefaultLocale($e);

    }

    public function setAclRole($e)
    {

        $routeMatch = $e->getRouteMatch();

        if($routeMatch->getParam('module') != __NAMESPACE__)
            return;

        /*$model = new Model\Customer($e->getApplication()->getServiceManager());

        if($logged = $model->getCurrent())
            $e->getViewModel()->aclRole = $logged->getRole();
        else
            $e->getViewModel()->aclRole = 'guest';*/

        $e->getViewModel()->aclRole = 'guest';

    }

    public function setDefaultLocale($e)
    {

        $config = $this->getConfig();

        \Locale::setDefault( $config['translator']['locale'] );

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
}
