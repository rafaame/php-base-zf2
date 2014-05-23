<?php

namespace Admin\Form\Admin;

use Zend\InputFilter\InputFilter,
	Zend\InputFilter\InputFilterProviderInterface,
	DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity,

	Admin\Entity;

class Edit extends Base implements InputFilterProviderInterface
{

	public function __construct($serviceLocator, $objectManager)
	{

		parent::__construct($serviceLocator, $objectManager);

		$this->get('password')->removeAttribute('required');

	}
	
    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {

        return array_merge(parent::getInputFilterSpecification(),
		[
			
            'password' =>
            [

            	'required' => false,

            	'filters' =>
                [

                	new \Zend\Filter\Null('string'),

                ]

            ]

        ]);

    }
	
}