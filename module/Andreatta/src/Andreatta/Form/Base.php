<?php

namespace Andreatta\Form;

use Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    ZfcBase\Form\ProvidesEventsForm,

    Doctrine\Common\Persistence\ObjectManager;

class Base extends ProvidesEventsForm
{
	
	/**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var ObjectManager
     */
    protected $objectManager;
	
	/**
     * Set serviceManager instance
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {

        $this->serviceLocator = $serviceLocator;

        return $this;

    }

    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {

        return $this->serviceLocator;

    }

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

    public function __construct($serviceLocator, $objectManager = null)
    {

        parent::__construct();

        $this->setServiceLocator($serviceLocator);
        
        if($objectManager !== null)
            $this->setObjectManager($objectManager);

    }

    protected function translate($string)
    {

        return $this->getServiceLocator()->get('translator')->translate($string);

    }

    protected function viewHelper($helper)
    {

        return $this->getServiceLocator()->get('ViewHelperManager')->get($helper);

    }
	
}

?>