<?php

namespace Admin\Form\Auth;

use Zend\InputFilter\InputFilter,
	Zend\InputFilter\InputFilterProviderInterface,
	DoctrineModule\Stdlib\Hydrator\DoctrineObject,

	Andreatta\Form\Base,
	Admin\Entity;

class Login extends Base implements InputFilterProviderInterface
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
				->add(array
				(
					
					'type' => '\Zend\Form\Element\Email',
					'name' => 'email',
					
					'attributes' => array
					(
						
						'required' => 'required',
						'placeholder' => __('Email'),
						'class' => 'form-control input-lg'
						
					),
					
				))
		
				->add(array
				(
					
					'type' => '\Zend\Form\Element\Password',
					'name' => 'password',
            
					'attributes' => array
					(
						'required' => 'required',
						'placeholder' => __('Password'),
						'class' => 'form-control input-lg'
					)
					
				));
		
	}

	/**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array
		(
			
            'email' => array
			(

                'required' => true,

                'validators' => array
				(
					
					new \Zend\Validator\EmailAddress(),
					
				)

            ),
			
            'password' => array
			(

                'required' => true,

            )
			
        );
    }
	
}