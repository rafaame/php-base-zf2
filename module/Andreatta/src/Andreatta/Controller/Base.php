<?php

namespace Andreatta\Controller;

use Zend\Mvc\Controller\AbstractActionController,

    Doctrine\Common\Persistence\ObjectManager;

class Base extends AbstractActionController
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

    protected function translate($string)
    {

        return $this->getServiceLocator()->get('translator')->translate($string);

    }

    protected function viewHelper($helper)
    {

        return $this->getServiceLocator()->get('ViewHelperManager')->get($helper);

    }

}
