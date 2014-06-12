<?php

namespace Admin\Model;

use Admin\Entity,
	Andreatta\Model\Base as Base;

class Configuration extends Base
{

	public function findOneByKey($key)
    {

        $objectManager = $this->getObjectManager();

        $entityName = str_replace('\Model\\', '\Entity\\', get_class($this));
        $entity = $objectManager
                    ->getRepository($entityName)
                    ->findOneBy(['key' => $key]);

        return $entity;

    }
	
	public static function get($key, $serviceLocator)
	{

		$instance = new Configuration($serviceLocator);

		$config = $instance->findOneByKey($key);
		$value = $config->getValue();

		return $value;

	}
	
}