<?php

namespace Admin\Form\Admin;

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
				->setObject(new Entity\Admin())
				->setHydrator(new DoctrineObject($objectManager))
				->setInputFilter(new InputFilter());
		
		$this

				->add(
				[
					
					'type' => '\Zend\Form\Element\Text',
					'name' => 'firstName',
					
					'options' => 
					[
						
						'label' => __('First Name'),
						
					],
					
					'attributes' => 
					[
						
						'required' => 'required',
						
					],
					
				])

				->add(
				[
					
					'type' => '\Zend\Form\Element\Text',
					'name' => 'lastName',
					
					'options' => 
					[
						
						'label' => __('Last Name'),
						
					],
					
					'attributes' => 
					[
						
						'required' => 'required',
						
					],
					
				])

				->add(
				[
					
					'type' => '\Zend\Form\Element\Email',
					'name' => 'email',
					
					'options' => 
					[
						
						'label' => __('Email'),
						
					],
					
					'attributes' => 
					[
						
						'required' => 'required',
						
					],
					
				])

				->add(
				[
					
					'type' => '\Zend\Form\Element\Password',
					'name' => 'password',
					
					'options' => 
					[
						
						'label' => __('Password'),
						
					],
            
					'attributes' => 
					[

						'required' => 'required',

					]
					
				]);
		
	}

	/**
     * @return array
     */
    public function getInputFilterSpecification()
    {

        return
		[
			
            'firstName' => 
			[

                'required' => true,

                'validators' => 
				[
					
					new \Zend\Validator\NotEmpty(),
					
				]

            ],

            'lastName' => 
			[

                'required' => true,

                'validators' => 
				[
					
					new \Zend\Validator\NotEmpty(),
					
				]

            ],

            'email' => 
			[

                'required' => true,

                'validators' => 
				[
					
					new \Zend\Validator\EmailAddress(),
					
				]

            ],

            'password' => 
			[

                'required' => true,

            ],

        ];

    }
	
}