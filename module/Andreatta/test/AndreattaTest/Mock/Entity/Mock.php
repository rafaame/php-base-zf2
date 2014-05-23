<?php

namespace AndreattaTest\Mock\Entity;

use Doctrine\ORM\Mapping as ORM;

/** 
 * @ORM\Entity
 * @ORM\Table(name="test")
 */
class Mock
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $id;

    /** @ORM\Column(type="string") */
    public $test;

    public function __construct()
    {

        $this->test = '';

    }

    public function getId()
    {

        return $this->id;

    }

    public function getTest()
    {

        return $this->test;

    }

    public function setTest($value)
    {

        $this->test = $value;

        return $this;

    }

}