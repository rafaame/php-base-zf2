<?php
namespace AdminTest\Entity;

use AndreattaTest\Framework\TestCase,

    Admin\Entity\Admin as Entity;

class AdminTest extends TestCase
{

    const SALT_LENGTH = 64;

    public function testInitialState()
    {

        $entity = new Entity();

        $this->assertNull($entity->id);
        $this->assertSame('', $entity->firstName);
        $this->assertSame('', $entity->lastName);
        $this->assertSame('admin', $entity->role);
        $this->assertSame('', $entity->email);
        $this->assertSame('', $entity->password);
        $this->assertInternalType('string', $entity->salt);
        $this->assertSame(self::SALT_LENGTH, strlen($entity->salt));

    }

    public function testGetFullname()
    {

        $firstName = 'First Name';
        $lastName = 'Last Name';
        $fullName = $firstName . ' ' . $lastName;

        $entity = new Entity();
        $entity
            ->setFirstName($firstName)
            ->setLastName($lastName);

        $this->assertSame($fullName, $entity->getFullname());

    }

    public function testSetPasswordWithHashing()
    {

        $password = 'test';

        $entity = new Entity();
        $entity->setPassword($password, true);

        $this->assertSame(Entity::hashPassword($entity, $password), $entity->getPassword());

    }

    public function testSetPasswordWithoutHashing()
    {

        $password = 'test';

        $entity = new Entity();
        $entity->setPassword($password, false);

        $this->assertSame($password, $entity->getPassword());

    }

    public function testSetNullPassword()
    {

        $password = 'test';

        $entity = new Entity();
        $entity->setPassword($password, true);

        $entity->setPassword(null, true);
        $this->assertSame(Entity::hashPassword($entity, $password), $entity->getPassword());

        $entity->setPassword(null, false);
        $this->assertSame(Entity::hashPassword($entity, $password), $entity->getPassword());

    }

    public function testHashPassword()
    {

        $password = 'test';

        $entity = new Entity();

        $this->assertSame( md5( $entity->getSalt() . $password ), Entity::hashPassword($entity, $password) );

    }

}