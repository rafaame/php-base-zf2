<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM,

	Andreatta\Entity\Base;

/** 
 * @ORM\Entity
 * @ORM\Table(name="configuration")
 */
class Configuration extends Base
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $id;

    /** @ORM\Column(type="string", name="_key") */
    public $key;
	
	/** @ORM\Column(type="string") */
    public $value;

    /** @ORM\Column(type="string") */
    public $label;

    public function __construct()
    {

    	$this->key = '';
    	$this->value = '';
    	$this->label = '';

    }

    public function getId()
    {

    	return $this->id;

    }
	
	/**
	 * Getter for key
	 *
	 * @return mixed
	 */
	public function getKey()
	{
	
	    return $this->key;
	
	}
	
	/**
	 * Setter for key
	 *
	 * @param mixed $key Value to set
	 *
	 * @return self
	 */
	public function setKey($key)
	{
	
	    $this->key = $key;
	
	    return $this;
	
	}

	/**
	 * Getter for value
	 *
	 * @return mixed
	 */
	public function getValue()
	{
	
	    return $this->value;
	
	}
	
	/**
	 * Setter for value
	 *
	 * @param mixed $value Value to set
	 *
	 * @return self
	 */
	public function setValue($value)
	{
	
	    $this->value = $value;
	
	    return $this;
	
	}
	
	/**
	 * Getter for label
	 *
	 * @return mixed
	 */
	public function getLabel()
	{
	
	    return $this->label;
	
	}
	
	/**
	 * Setter for label
	 *
	 * @param mixed $label Value to set
	 *
	 * @return self
	 */
	public function setLabel($label)
	{
	
	    $this->label = $label;
	
	    return $this;
	
	}

}

?>