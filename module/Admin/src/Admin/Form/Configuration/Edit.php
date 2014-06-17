<?php

namespace Admin\Form\Configuration;

use Zend\InputFilter\InputFilter,
	Zend\InputFilter\InputFilterProviderInterface,
	DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity,

	Admin\Entity;

class Edit extends Base implements InputFilterProviderInterface
{

	public function __construct($serviceLocator, $objectManager)
	{

		parent::__construct($serviceLocator, $objectManager);

		$this->get('label')->setAttribute('disabled', 'disabled');

	}
	
    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {

        return array_merge(parent::getInputFilterSpecification(),
		[
			
            'label' => 
			[

                'required' => false,

                'validators' => 
				[
					
					
					
				]

            ],

        ]);

    }
	
}