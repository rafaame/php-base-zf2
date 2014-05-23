<?php

namespace Andreatta\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\ServiceManager\ServiceLocatorAwareInterface,

    Doctrine\Common\Persistence\ObjectManager;

class Base extends AbstractPlugin implements ServiceLocatorAwareInterface
{

    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

	/**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Sets the ObjectManager
     *
     * @param ObjectManager $em
     * @access protected
     * @return PostController
     */
    protected function setObjectManager(ObjectManager $em) 
    {

        $this->objectManager = $em;

        return $this;

    }

    /**
     * Returns the ObjectManager
     *
     * Fetches the ObjectManager from ServiceLocator if it has not been initiated
     * and then returns it
     *
     * @access protected
     * @return ObjectManager
     */
    protected function getObjectManager() 
    {

        if ($this->objectManager === null)
            $this->setObjectManager($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));

        return $this->objectManager;

    }

    public function __invoke($serviceLocator = null, $objectManager = null)
    {

        if($serviceLocator !== null)
            $this->setServiceLocator($serviceLocator);

        if($objectManager !== null)
            $this->setObjectManager($objectManager);

        return $this;

    }

}
