<?php

namespace Andreatta\Form;

use Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface,

    Traversable,
    Zend\Form\Form,
    Zend\EventManager\EventManagerInterface,
    Zend\EventManager\EventManager,

    Doctrine\Common\Persistence\ObjectManager;

class Base extends Form
{
    
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var EventManagerInterface
     */
    protected $events;

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
     * Set the event manager instance used by this context
     *
     * @param  EventManagerInterface $events
     * @return mixed
     */
    public function setEventManager(EventManagerInterface $events)
    {

        $this->events = $events;

        return $this;

    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {

        if (!$this->events instanceof EventManagerInterface)
        {

            $identifiers = [__CLASS__, get_called_class()];

            if(isset($this->eventIdentifier))
            {

                if
                (

                    is_string($this->eventIdentifier) || 
                    is_array($this->eventIdentifier) || 
                    ($this->eventIdentifier instanceof Traversable)

                )
                    $identifiers = array_unique($identifiers + (array) $this->eventIdentifier);
                elseif(is_object($this->eventIdentifier))
                    $identifiers[] = $this->eventIdentifier;

                // silently ignore invalid eventIdentifier types
            }

            $this->setEventManager(new EventManager($identifiers));

        }
        
        return $this->events;

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