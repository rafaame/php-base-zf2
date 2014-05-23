<?php

namespace Andreatta\Controller;

use Zend\Mvc\Controller\AbstractRestfulController,

    Doctrine\Common\Persistence\ObjectManager;

class BaseRest extends AbstractRestfulController
{

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

}
