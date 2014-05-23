<?php

namespace Andreatta\Model;

use Zend\ServiceManager\ServiceLocatorAwareInterface,
	Zend\ServiceManager\ServiceLocatorInterface,

    Doctrine\Common\Persistence\ObjectManager,

    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator;

abstract class Base
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

        $this->setServiceLocator($serviceLocator);
        
        if($objectManager !== null)
            $this->setObjectManager($objectManager);

    }

    public function flush()
    {

        $objectManager = $this->getObjectManager();

        $objectManager->flush();
        
    }

    public function paginator($countPerPage = 5)
    {

        $objectManager = $this->getObjectManager();

        $entityName = str_replace('\Model\\', '\Entity\\', get_class($this));
        $query = $objectManager->createQuery("SELECT u FROM $entityName u");

        $paginator = new Paginator(new DoctrinePaginator(new ORMPaginator($query)));
        $paginator
            ->setCurrentPageNumber(1)
            ->setItemCountPerPage($countPerPage);

        return $paginator;

    }

    public function create()
    {

        $entityName = str_replace('\Model\\', '\Entity\\', get_class($this));
        $entity = new $entityName();

        return $entity;

    }
    
    public function save($entity, $flush = true)
    {

        $objectManager = $this->getObjectManager();

        $objectManager->persist($entity);

        if($flush)
            $objectManager->flush();

    }

    public function remove($entity, $flush = true)
    {

        $objectManager = $this->getObjectManager();

        $objectManager->remove($entity);

        if($flush)
            $objectManager->flush();

    }

    public function findAll(array $orderBy = null, $limit = null, $offset = null)
    {

        $objectManager = $this->getObjectManager();

        $entityName = str_replace('\Model\\', '\Entity\\', get_class($this));
        $entity = $objectManager
                    ->getRepository($entityName)
                    ->findBy([], $orderBy, $limit, $offset);

        return $entity;

    }

    public function findOneById($id)
    {

        $objectManager = $this->getObjectManager();

        $entityName = str_replace('\Model\\', '\Entity\\', get_class($this));
        $entity = $objectManager
                    ->getRepository($entityName)
                    ->findOneBy(['id' => $id]);

        return $entity;

    }
	
}

?>
