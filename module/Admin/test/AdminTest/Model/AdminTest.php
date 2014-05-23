<?php
namespace AdminTest\Model;

use AndreattaTest\Framework\TestCase,
    AndreattaTest\Util\Util,
    
    AdminTest\Bootstrap,

    Admin\Model\Admin as Model,
    Admin\Entity\Admin as Entity;

class AdminTest extends TestCase
{

    private static $ENTITY_1 = null;
    const ENTITY_FIRST_NAME_1 = 'First Name';
    const ENTITY_LAST_NAME_1 = 'Last Name';
    const ENTITY_ROLE_1 = 'admin';
    const ENTITY_EMAIL_1 = 'test@email.com';
    const ENTITY_PASSWORD_1 = 'test';

    public function dataSetUpForLogin()
    {

        $serviceLocator = Bootstrap::getServiceManager();
        $objectManager = Bootstrap::getObjectManager();

        //Create a test user
        self::$ENTITY_1 = new Entity();
        self::$ENTITY_1
            ->setFirstName(self::ENTITY_FIRST_NAME_1)
            ->setLastName(self::ENTITY_LAST_NAME_1)
            ->setRole(self::ENTITY_ROLE_1)
            ->setEmail(self::ENTITY_EMAIL_1)
            ->setPassword(self::ENTITY_PASSWORD_1, true);
        $objectManager->persist(self::$ENTITY_1);

        $objectManager->flush();

    }

    public function dataTearDownForLogin()
    {

        self::$ENTITY_1 = null;

    }

    public function testGetAuthenticationService()
    {

        $serviceLocator = Bootstrap::getServiceManager();
        $objectManager = Bootstrap::getObjectManager();

        $model = new Model($serviceLocator, $objectManager);

        $method = new \ReflectionMethod($model, 'getAuthenticationService');
        $method->setAccessible(true);
 
        $this->assertInstanceOf('Zend\Authentication\AuthenticationService', $method->invoke($model));

    }

    public function testGetAuthenticationAdapter()
    {

        $serviceLocator = Bootstrap::getServiceManager();
        $objectManager = Bootstrap::getObjectManager();

        $model = new Model($serviceLocator, $objectManager);

        $method = new \ReflectionMethod($model, 'getAuthenticationAdapter');
        $method->setAccessible(true);
 
        $this->assertInstanceOf('DoctrineModule\Authentication\Adapter\ObjectRepository', $method->invoke($model));

    }

    public function testAuthenticateFalseIncorrectIdentity()
    {

        $this->disableNextSetup();
        $this->dataSetUpForLogin();

        $serviceLocator = Bootstrap::getServiceManager();
        $objectManager = Bootstrap::getObjectManager();

        $model = new Model($serviceLocator, $objectManager);

        $success = $model->authenticate('incorrect@email.com', self::ENTITY_PASSWORD_1);

        $this->assertFalse($success);

        return $model;

    }

    /**
     * @depends testAuthenticateFalseIncorrectIdentity
     */
    public function testIsLoggedFalseIncorrectIdentity(Model $model)
    {

        $this->disableNextSetup();

        $this->assertFalse($model->isLogged());

    }

    /**
     * @depends testAuthenticateFalseIncorrectIdentity
     */
    public function testGetCurrentNullIncorrectIdentity(Model $model)
    {

        $this->disableNextSetup();

        $this->assertNull($model->getCurrent());

    }

    /**
     * @depends testAuthenticateFalseIncorrectIdentity
     */
    public function testLogoutFalseIncorrectIdentity(Model $model)
    {

        $this->assertFalse($model->logout());
        $this->assertFalse($model->isLogged());

        $this->dataTearDownForLogin();

    }

    public function testAuthenticateFalseIncorrectCredential()
    {

        $this->disableNextSetup();
        $this->dataSetUpForLogin();

        $serviceLocator = Bootstrap::getServiceManager();
        $objectManager = Bootstrap::getObjectManager();

        $model = new Model($serviceLocator, $objectManager);

        $success = $model->authenticate(self::ENTITY_EMAIL_1, 'incorrect');

        $this->assertFalse($success);

        return $model;

    }

    /**
     * @depends testAuthenticateFalseIncorrectCredential
     */
    public function testIsLoggedFalseIncorrectCredential(Model $model)
    {

        $this->disableNextSetup();

        $this->assertFalse($model->isLogged());

    }

    /**
     * @depends testAuthenticateFalseIncorrectCredential
     */
    public function testGetCurrentNullIncorrectCredential(Model $model)
    {

        $this->disableNextSetup();

        $this->assertNull($model->getCurrent());

    }

    /**
     * @depends testAuthenticateFalseIncorrectCredential
     */
    public function testLogoutFalseIncorrectCredential(Model $model)
    {

        $this->assertFalse($model->logout());
        $this->assertFalse($model->isLogged());

        $this->dataTearDownForLogin();

    }

    public function testAuthenticateTrue()
    {

        $this->disableNextSetup();
        $this->dataSetUpForLogin();

        $serviceLocator = Bootstrap::getServiceManager();
        $objectManager = Bootstrap::getObjectManager();

        $model = new Model($serviceLocator, $objectManager);

        $success = $model->authenticate(self::ENTITY_EMAIL_1, self::ENTITY_PASSWORD_1);

        $this->assertTrue($success);

        return $model;

    }

    /**
     * @depends testAuthenticateTrue
     */
    public function testIsLoggedTrue(Model $model)
    {

        $this->disableNextSetup();

        $this->assertTrue($model->isLogged());

    }

    /**
     * @depends testAuthenticateTrue
     */
    public function testGetCurrentSuccess(Model $model)
    {

        $this->disableNextSetup();

        $entity = $model->getCurrent();

        $this->assertInstanceOf('Admin\Entity\Admin', $entity);
        $this->assertSame(self::$ENTITY_1, $entity);

    }

    /**
     * @depends testAuthenticateTrue
     */
    public function testLogoutTrue(Model $model)
    {

        $this->assertTrue($model->logout());
        $this->assertFalse($model->isLogged());

        $this->dataTearDownForLogin();

    }

    public function testIsLoggedFalseNotLogged()
    {

        $this->disableNextSetup();
        $this->dataSetUpForLogin();

        $serviceLocator = Bootstrap::getServiceManager();
        $objectManager = Bootstrap::getObjectManager();

        $model = new Model($serviceLocator, $objectManager);

        $this->assertFalse($model->isLogged());

        return $model;

    }

    /**
     * @depends testIsLoggedFalseNotLogged
     */
    public function testLogoutFalseNotLogged(Model $model)
    {

        $this->assertFalse($model->logout());
        $this->assertFalse($model->isLogged());

        $this->dataTearDownForLogin();

    }

    public function testPaginator()
    {

        $serviceLocator = Bootstrap::getServiceManager();
        $objectManager = Bootstrap::getObjectManager();

        $entities = [];
        $countPerPage = 5;
        $pages = 10;

        for($i = 0; $i < $pages; $i++)
        {

            $entities[$i] = [];

            for($j = 0; $j < $countPerPage; $j++)
            {

                $entities[$i][$j] = new Entity();
                $entities[$i][$j]
                    ->setFirstName(Util::randomString())
                    ->setLastName(Util::randomString())
                    ->setRole(Util::randomString(10, 'lower'))
                    ->setEmail(Util::randomEmail())
                    ->setPassword(Util::randomString(), true);
                
                $objectManager->persist($entities[$i][$j]);

            }

        }

        $objectManager->flush();

        $model = new Model($serviceLocator, $objectManager);
        $paginator = $model->paginator($countPerPage);

        $this->assertSame($pages, $paginator->count(), 'Number of pages does not match.');
        $this->assertSame($pages * $countPerPage, $paginator->getTotalItemCount(), 'Total item count does not match.');
        $this->assertSame(1, $paginator->getCurrentPageNumber(), 'Not starting on the first page.');

        for($i = 0; $i < $pages; $i++)
        {

            $paginator->setCurrentPageNumber($i + 1);
            $this->assertSame($i + 1, $paginator->getCurrentPageNumber());

            $items = $paginator->getCurrentItems();

            $j = 0;
            foreach($items as $item)
            {

                if($j >= $countPerPage)
                    $this->fail('Page ' . ($j + 1) . 'has more items than the allowed items per page (' . $countPerPage . ').');

                $this->assertInstanceOf('Admin\Entity\Admin', $item);
                $this->assertSame($entities[$i][$j], $item);

                $j++;

            }

        }

    }

    public function testSavePlainPassword()
    {

        $serviceLocator = Bootstrap::getServiceManager();
        $objectManager = Bootstrap::getObjectManager();

        $password = Util::randomString();

        $model = new Model($serviceLocator, $objectManager);
        $entity = new Entity();
        $entity
            ->setFirstName(Util::randomString())
            ->setLastName(Util::randomString())
            ->setRole(Util::randomString(10, 'lower'))
            ->setEmail(Util::randomEmail())
            //Call this method like the hydrator would do
            ->setPassword($password);

        //Save but don't hash the password
        $model->save($entity, false);

        $this->assertSame($password, $entity->getPassword());

    }

    public function testSaveHashPassword()
    {

        $serviceLocator = Bootstrap::getServiceManager();
        $objectManager = Bootstrap::getObjectManager();

        $password = Util::randomString();

        $model = new Model($serviceLocator, $objectManager);
        $entity = new Entity();
        $entity
            ->setFirstName(Util::randomString())
            ->setLastName(Util::randomString())
            ->setRole(Util::randomString(10, 'lower'))
            ->setEmail(Util::randomEmail())
            //Call this method like the hydrator would do
            ->setPassword($password);

        //Save and hash the password
        $model->save($entity, true);

        $this->assertSame( Entity::hashPassword($entity, $password), $entity->getPassword() );

    }

}