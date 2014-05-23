<?php

namespace Admin\Form\Admin;

use Zend\InputFilter\InputFilter,
	Zend\InputFilter\InputFilterProviderInterface,
	DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity,

	Admin\Entity;

class Add extends Base implements InputFilterProviderInterface
{

	public function __construct($serviceLocator, $objectManager)
	{

		parent::__construct($serviceLocator, $objectManager);

	}
	
    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {

        return array_merge(parent::getInputFilterSpecification(),
		[
			
            

        ]);

    }
	
}