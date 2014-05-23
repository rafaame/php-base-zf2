<?php

namespace AndreattaTest\Framework;

use Zend\ServiceManager\ServiceManager,

    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Tools\SchemaTool,

    AndreattaTest\Bootstrap,

    PHPUnit_Framework_TestCase;

/**
 * Base test case for tests using the entity manager
 */
class TestCase extends PHPUnit_Framework_TestCase
{

    protected static $serviceManager;
    protected static $objectManager;

    /**
     * @var boolean
     */
    protected $hasDb = false;

    protected static $sharedSession = null;

    /**
     * @var boolean
     */
    protected static $disableNextSetUp = false;

    public function getObjectManager()
    {

        if (self::$objectManager)
            return self::$objectManager;

        $serviceManager = $this->getServiceManager();
        $serviceManager->get('doctrine.entity_resolver.orm_default');

        self::$objectManager = $serviceManager->get('doctrine.entitymanager.orm_default');

        return self::$objectManager;

    }

    public static function setObjectManager($objectManager)
    {

        self::$objectManager = $objectManager;

    }

    public function getServiceManager()
    {

        return self::$serviceManager;

    }

    public static function setServiceManager($serviceManager)
    {

        self::$serviceManager = $serviceManager;

    }

    /**
     * Creates a database if not done already.
     */
    public function createDb()
    {

        if ($this->hasDb)
            return;

        $objectManager = $this->getObjectManager();

        $tool = new SchemaTool($objectManager);
        $tool->updateSchema( $objectManager->getMetadataFactory()->getAllMetadata() );

        $this->hasDb = true;

    }

    /**
     * Drops existing database
     */
    public function dropDb()
    {

        $objectManager = $this->getObjectManager();

        $tool = new SchemaTool($objectManager);
        $tool->dropSchema( $objectManager->getMetadataFactory()->getAllMetadata() );
        $objectManager->clear();

        $this->hasDb = false;

    }

    public function disableNextSetUp()
    {

        self::$disableNextSetUp = true;

    }

    public function setUp()
    {

        $_SESSION = self::$sharedSession;

        if(self::$disableNextSetUp)
        {

            self::$disableNextSetUp = false;
            return false;

        }

        //Reset session at each setup
        $_SESSION = [];

        $this->createDb();

        return true;

    }

    public function tearDown()
    {

        //Save session, if needed we reset it in the next setup
        self::$sharedSession = $_SESSION;

        //If we want to disable next setup, we cannot do the current teardown
        if(self::$disableNextSetUp)
            return false;

        $this->dropDb();

        return true;

    }

}