<?php

namespace Application\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\Session\Container;

class ApplicationSession extends AbstractPlugin
{

    protected static $sessionContainer = null;

    public function getSession()
    {

        if(self::$sessionContainer === null)
            self::$sessionContainer = new Container('Application');

        return self::$sessionContainer;

    }

    public function __invoke()
    {

        return $this->getSession();

    }

}
