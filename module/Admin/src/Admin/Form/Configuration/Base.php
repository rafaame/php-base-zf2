<?php

namespace Admin\Form\Configuration;

use Zend\InputFilter\InputFilter,
	Zend\InputFilter\InputFilterProviderInterface,
	DoctrineModule\Stdlib\Hydrator\DoctrineObject,

	Andreatta\Form\Base as FormBase,
	Admin\Entity;

class Base extends FormBase implements InputFilterProviderInterface
{
	
    public function __construct($serviceLocator, $objectManager)
    {

        parent::__construct($serviceLocator, $objectManager);
		
        $this
        		//@FIXME: does the form 'action' should be here or in the view as it is?
				->setAttribute('method', 'post')
				->setObject(new Entity\Configuration())
				->setHydrator(new DoctrineObject($objectManager))
				->setInputFilter(new InputFilter());
		
		$this

				->add(
				[
					
					'type' => '\Zend\Form\Element\Text',
					'name' => 'key',
					
					'options' => 
					[
						
						'label' => __('Key'),
						
					],
					
					'attributes' => 
					[
						
						'required' => 'required',
						
					],
					
				])

				->add(
				[
					
					'type' => '\Zend\Form\Element\Text',
					'name' => 'value',
					
					'options' => 
					[
						
						'label' => __('Value'),
						
					],
					
					'attributes' => 
					[
						
						'required' => 'required',
						
					],
					
				])

				->add(
				[
					
					'type' => '\Zend\Form\Element\Text',
					'name' => 'label',
					
					'options' => 
					[
						
						'label' => __('Label'),
						
					],
					
					'attributes' => 
					[
						
						'required' => 'required',
						
					],
					
				]);
		
	}

	/**
     * @return array
     */
    public function getInputFilterSpecification()
    {

        return
		[

			'key' => 
			[

                'required' => true,

                'validators' => 
				[
					
					new \Zend\Validator\NotEmpty(),
					
				]

            ],

            'value' => 
			[

                'required' => false,

            ],
			
            'label' => 
			[

                'required' => true,

                'validators' => 
				[
					
					new \Zend\Validator\NotEmpty(),
					
				]

            ],

        ];

    }
	
}