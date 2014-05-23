<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM,

	Andreatta\Entity\Base;

/** 
 * @ORM\Entity
 * @ORM\Table(name="admin")
 */
class Admin extends Base
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $id;

    /** @ORM\Column(type="string", name="first_name") */
    public $firstName;
	
	/** @ORM\Column(type="string", name="last_name") */
    public $lastName;

    /** @ORM\Column(type="string") */
    public $role;
	
	/** @ORM\Column(type="string") */
    public $email;
	
	/** @ORM\Column(type="string") */
    public $password;

    /** @ORM\Column(type="string") */
    public $salt;

    public function __construct()
    {

    	$this->firstName = '';
    	$this->lastName = '';
    	$this->role = 'admin';
    	$this->email = '';
    	$this->password = '';
    	$this->salt = $this->generateSalt();

    }

    public function getId()
    {

    	return $this->id;

    }
	
	public function getFirstName()
	{
		
		return $this->firstName;
		
	}

	public function setFirstName($value)
	{

		$this->firstName = $value;

		return $this;

	}

	public function getLastName()
	{
		
		return $this->lastName;
		
	}

	public function setLastName($value)
	{

		$this->lastName = $value;

		return $this;

	}

	public function getFullname()
	{

		return $this->getFirstName() . ' ' . $this->getLastName();

	}

	public function getRole()
	{
		
		return $this->role;
		
	}

	public function setRole($value)
	{

		$this->role = $value;

		return $this;

	}
	
	public function getEmail()
	{
		
		return $this->email;
		
	}

	public function setEmail($value)
	{

		$this->email = $value;

		return $this;

	}
	
	public function getPassword()
	{
		
		return $this->password;
		
	}

	public function setPassword($value, $doHash = false)
	{

		//When in an editing form, in the case the user doesn't want to change the password (i.e. leave it blank),
		//there will be a \Zend\Filter\Null, so the form will hydrate the entity with a null password. We simply ignore it.
		if($value === null)
			return;

		if($doHash)
			$this->password = self::hashPassword($this, $value);
		else
			$this->password = $value;

		return $this;

	}

	public static function hashPassword(Admin $entity, $password)
	{

		return md5( $entity->getSalt() . $password );
		
	}

	public function getSalt()
	{
		
		return $this->salt;
		
	}

	public function setSalt($value)
	{

		$this->salt = $value;

		return $this;

	}

	function generateSalt() 
	{

		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array();
		$alphaLength = strlen($alphabet) - 1;

		for ($i = 0; $i < 64; $i++) 
		{

			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];

		}

		return implode($pass);

	}

}

?>
