<?php
namespace ApplicationTest\Model;

use AndreattaTest\Framework\TestCase,
    AndreattaTest\Util\Util,

    ApplicationTest\Util\DataGenerator,

    Application\Model\Customer as Model,
    Application\Entity\Customer as Entity;

class CustomerTest extends TestCase
{

    private static $ENTITY_1 = null;
    const ENTITY_EMAIL_1 = 'test@email.com';
    const ENTITY_PASSWORD_1 = 'test';

    public function dataSetUpForLogin()
    {

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

        $data = DataGenerator::generateTestingData($objectManager);

        self::$ENTITY_1 = $data['customers'][0]['entity'];
        self::$ENTITY_1
            ->setEmail(self::ENTITY_EMAIL_1)
            ->setPassword(self::ENTITY_PASSWORD_1, true)
            ->setActive(true)
            ->setNewsletter(true)
            ->setDebugger(true);
        $objectManager->persist(self::$ENTITY_1);

        $objectManager->flush();

    }

    public function dataTearDownForLogin()
    {

        self::$ENTITY_1 = null;

    }

    public function testGetAuthenticationService()
    {

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

        $model = new Model($serviceLocator, $objectManager);

        $method = new \ReflectionMethod($model, 'getAuthenticationService');
        $method->setAccessible(true);
 
        $this->assertInstanceOf('Zend\Authentication\AuthenticationService', $method->invoke($model));

    }

    public function testGetAuthenticationAdapter()
    {

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

        $model = new Model($serviceLocator, $objectManager);

        $method = new \ReflectionMethod($model, 'getAuthenticationAdapter');
        $method->setAccessible(true);
 
        $this->assertInstanceOf('DoctrineModule\Authentication\Adapter\ObjectRepository', $method->invoke($model));

    }

    public function testAuthenticateFalseIncorrectIdentity()
    {

        $this->disableNextSetup();
        $this->dataSetUpForLogin();

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

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

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

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

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

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

        $this->assertInstanceOf('Application\Entity\Customer', $entity);
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

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

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

    public function testSavePlainPassword()
    {

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

        $data = DataGenerator::generateTestingData($objectManager, ['customers' => 1]);

        $password = Util::randomString();

        $model = new Model($serviceLocator, $objectManager);
        $entity = $data['customers'][0]['entity'];
        $entity
            ->setEmail(Util::randomEmail())
            //Call this method like the hydrator would do
            ->setPassword($password);

        //Save but don't hash the password
        $model->save($entity, false);

        $this->assertSame($password, $entity->getPassword());

    }

    public function testSaveHashPassword()
    {

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

        $data = DataGenerator::generateTestingData($objectManager, ['customers' => 1]);

        $password = Util::randomString();

        $model = new Model($serviceLocator, $objectManager);
        $entity = $data['customers'][0]['entity'];
        $entity
            ->setEmail(Util::randomEmail())
            //Call this method like the hydrator would do
            ->setPassword($password);

        //Save and hash the password
        $model->save($entity, true);

        $this->assertSame( Entity::hashPassword($entity, $password), $entity->getPassword() );

    }

    public function testRegisterMissingPhoneAreaCode()
    {

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

        $model = new Model($serviceLocator, $objectManager);

        $data =
        [

            'name' => Util::randomString(),
            'email' => Util::randomEmail(),
            'password' => Util::randomString(),
            'phone-mobile-areacode' => Util::randomInt(),
            'phone-mobile-number' => Util::randomInt(),
            'phone-number' => Util::randomInt(),
            'address-street' => Util::randomString(),
            'address-number' => Util::randomInt(),
            'address-complement' => Util::randomString(),
            'address-zipcode' => Util::randomInt(),
            'address-reference' => Util::randomString(),

        ];

        $customer = $model->register($data);
        $addresses = $customer->getAddresses();
        $phones = $customer->getPhones();

        $this->assertInstanceOf('Application\Entity\Customer', $customer);
        $this->assertSame(1, count($phones), 'Registered phones count != 1.');
        $this->assertSame($data['phone-mobile-areacode'], $phones[0]->getAreaCode(), 'Mobile phone area code does not match.');
        $this->assertSame($data['phone-mobile-number'], $phones[0]->getNumber(), 'Mobile phone number does not match.');

    }

    public function testRegisterMissingPhoneNumber()
    {

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

        $model = new Model($serviceLocator, $objectManager);

        $data =
        [

            'name' => Util::randomString(),
            'email' => Util::randomEmail(),
            'password' => Util::randomString(),
            'phone-mobile-areacode' => Util::randomInt(),
            'phone-mobile-number' => Util::randomInt(),
            'phone-areacode' => Util::randomInt(),
            'address-street' => Util::randomString(),
            'address-number' => Util::randomInt(),
            'address-complement' => Util::randomString(),
            'address-zipcode' => Util::randomInt(),
            'address-reference' => Util::randomString(),

        ];

        $customer = $model->register($data);
        $addresses = $customer->getAddresses();
        $phones = $customer->getPhones();

        $this->assertInstanceOf('Application\Entity\Customer', $customer);
        $this->assertSame(1, count($phones), 'Registered phones count != 1.');
        $this->assertSame($data['phone-mobile-areacode'], $phones[0]->getAreaCode(), 'Mobile phone area code does not match.');
        $this->assertSame($data['phone-mobile-number'], $phones[0]->getNumber(), 'Mobile phone number does not match.');

    }

    public function testRegisterMissingAddressComplement()
    {

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

        $model = new Model($serviceLocator, $objectManager);

        $data =
        [

            'name' => Util::randomString(),
            'email' => Util::randomEmail(),
            'password' => Util::randomString(),
            'phone-mobile-areacode' => Util::randomInt(),
            'phone-mobile-number' => Util::randomInt(),
            'phone-areacode' => Util::randomInt(),
            'phone-number' => Util::randomInt(),
            'address-street' => Util::randomString(),
            'address-number' => Util::randomInt(),
            'address-zipcode' => Util::randomInt(),
            'address-reference' => Util::randomString(),

        ];

        $customer = $model->register($data);
        $addresses = $customer->getAddresses();
        $phones = $customer->getPhones();

        $this->assertInstanceOf('Application\Entity\Customer', $customer);
        $this->assertSame(1, count($addresses), 'Registered addresses count != 1.');
        $this->assertSame($data['address-street'], $addresses[0]->getStreet(), 'Address street does not match.');
        $this->assertSame($data['address-number'], $addresses[0]->getNumber(), 'Address number does not match.');
        $this->assertSame('', $addresses[0]->getComplement(), 'Address complement not empty.');
        $this->assertSame($data['address-zipcode'], $addresses[0]->getZipcode(), 'Address zipcode does not match.');
        $this->assertSame($data['address-reference'], $addresses[0]->getReference(), 'Address reference does not match.');

    }

    public function testRegisterMissingAddressReference()
    {

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

        $model = new Model($serviceLocator, $objectManager);

        $data =
        [

            'name' => Util::randomString(),
            'email' => Util::randomEmail(),
            'password' => Util::randomString(),
            'phone-mobile-areacode' => Util::randomInt(),
            'phone-mobile-number' => Util::randomInt(),
            'phone-areacode' => Util::randomInt(),
            'phone-number' => Util::randomInt(),
            'address-street' => Util::randomString(),
            'address-number' => Util::randomInt(),
            'address-zipcode' => Util::randomInt(),
            'address-complement' => Util::randomString(),

        ];

        $customer = $model->register($data);
        $addresses = $customer->getAddresses();
        $phones = $customer->getPhones();

        $this->assertInstanceOf('Application\Entity\Customer', $customer);
        $this->assertSame(1, count($addresses), 'Registered addresses count != 1.');
        $this->assertSame($data['address-street'], $addresses[0]->getStreet(), 'Address street does not match.');
        $this->assertSame($data['address-number'], $addresses[0]->getNumber(), 'Address number does not match.');
        $this->assertSame($data['address-complement'], $addresses[0]->getComplement(), 'Address complement does not match.');
        $this->assertSame($data['address-zipcode'], $addresses[0]->getZipcode(), 'Address zipcode does not match.');
        $this->assertSame('', $addresses[0]->getReference(), 'Address reference not empty.');

    }

    public function testRegisterAllData()
    {

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

        $model = new Model($serviceLocator, $objectManager);

        $data =
        [

            'name' => Util::randomString(),
            'email' => Util::randomEmail(),
            'password' => Util::randomString(),
            'phone-mobile-areacode' => Util::randomInt(),
            'phone-mobile-number' => Util::randomInt(),
            'phone-areacode' => Util::randomInt(),
            'phone-number' => Util::randomInt(),
            'address-street' => Util::randomString(),
            'address-number' => Util::randomInt(),
            'address-complement' => Util::randomString(),
            'address-zipcode' => Util::randomInt(),
            'address-reference' => Util::randomString(),

        ];

        $customer = $model->register($data);
        $addresses = $customer->getAddresses();
        $phones = $customer->getPhones();

        $this->assertInstanceOf('Application\Entity\Customer', $customer);
        $this->assertSame($data['name'], $customer->getName(), 'Name does not match.');
        $this->assertSame($data['email'], $customer->getEmail(), 'Email does not match.');
        $this->assertSame(Entity::hashPassword($customer, $data['password']), $customer->getPassword(), 'Password hash does not match.');
        $this->assertSame(2, count($phones), 'Registered phones count != 2.');
        $this->assertSame($data['phone-mobile-areacode'], $phones[0]->getAreaCode(), 'Mobile phone area code does not match.');
        $this->assertSame($data['phone-mobile-number'], $phones[0]->getNumber(), 'Mobile phone number does not match.');
        $this->assertSame($data['phone-areacode'], $phones[1]->getAreaCode(), 'Phone area code does not match.');
        $this->assertSame($data['phone-number'], $phones[1]->getNumber(), 'Phone number does not match.');
        $this->assertSame(1, count($addresses), 'Registered addresses count != 1.');
        $this->assertSame($data['address-street'], $addresses[0]->getStreet(), 'Address street does not match.');
        $this->assertSame($data['address-number'], $addresses[0]->getNumber(), 'Address number does not match.');
        $this->assertSame($data['address-complement'], $addresses[0]->getComplement(), 'Address complement does not match.');
        $this->assertSame($data['address-zipcode'], $addresses[0]->getZipcode(), 'Address zipcode does not match.');
        $this->assertSame($data['address-reference'], $addresses[0]->getReference(), 'Address reference does not match.');

    }

}