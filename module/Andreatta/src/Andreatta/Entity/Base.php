<?php

namespace Andreatta\Entity;

use Doctrine\ORM\Mapping as ORM;

//@FIXME won't work because method hydrateByValue of DoctrineObject check if the method exists with method_exists()
abstract class Base
{

    public function __call($name, $args) 
    {

        $initials = substr($name, 0, 3);
        $property = lcfirst(substr($name, 3));

        switch($initials)
        {

            case 'get':

                return $this->$property;

            case 'set':

                $this->$property = $args[0];

                return $this;

        }

    }

}

?>
