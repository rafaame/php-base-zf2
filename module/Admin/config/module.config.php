<?php

namespace Admin;

return 
[

	'doctrine' =>
	[
		
		'driver' =>
		[
			
			__NAMESPACE__ . '_driver' =>
			[
				
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => [ __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity' ]
				
			],
			
			'orm_default' =>
			[
				
				'drivers' =>
				[
					
					__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
					
				],
				
			],
			
		],
		
		'authentication' =>
		[
			
			//@FIXME change the name of authentication service to 'admin'
			'orm_default' =>
			[
				
				'object_manager' => 'Doctrine\ORM\EntityManager',
				'identity_class' => 'Admin\Entity\Admin',
				'identity_property' => 'email',
				'credential_property' => 'password',
				'credentialCallable' => 'Admin\Entity\Admin::hashPassword',
				'storage' => 'Admin\Auth\Storage\Session',
				
			],
			
		],
		
	],
	
	'router' =>
	[
		
		'routes' =>
		[
			
			'admin' =>
			[
				
				'type' => 'Segment',
				'options' =>
				[
					
					//@FIXME: http://stackoverflow.com/questions/20624602/zend-framework-2-wildcard-route
					'route' => '/admin[/:controller[/:action]][/]',
					
					'constraints' =>
					[

						'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',

					],
					
					'defaults' =>
					[

						'__NAMESPACE__' => 'Admin\Controller',
						'module' => 'Admin',
						'controller' => 'Index',
						'action' => 'index',

					],
					
				],
				
				'may_terminate' => true,
				'child_routes' =>
				[
					
					'wildcard' =>
					[
						
						'type' => 'Wildcard'
					
					],
					
				],

				'priority' => 1000
				
			],
			
		],
		
	],

	'console' => 
	[

		'router' =>
		[

			'routes' =>
			[

				/*'email-campaign-dispatch' => 
				[

					'options' => 
					[

						'route' => 'admin email-campaign dispatch-step <step-size>',

						'defaults' => 
						[

							'__NAMESPACE__' => 'Admin\Controller',
							'module' => 'Admin',
							'controller' => 'EmailCampaign',
							'action' => 'dispatch-step',

						],

					],

				],*/

			],

		],

	],
	
	'service_manager' =>
	[

		'abstract_factories' =>
		[
		
			'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			'Zend\Log\LoggerAbstractServiceFactory',
			
		],

		'factories' =>
		[

			'Admin\Navigation' => 'Admin\Navigation\Service\AdminNavigationFactory',

			'Admin\Auth' => function($sm)
			{

                return $sm->get('doctrine.authenticationservice.orm_default');
				
			},

			'Admin\Auth\Storage\Session' => function($sm)
            {

                return new \Zend\Authentication\Storage\Session('Admin\Auth');

            },

		],
		
		'aliases' =>
		[
			
			'translator' => 'MvcTranslator',
			
		],
		
		'invokables' =>
		[

			
			
		],

	],
	
	'controllers' =>
	[

		'invokables' =>
		[

			'Admin\Controller\Index' => 'Admin\Controller\IndexController',
			'Admin\Controller\Auth' => 'Admin\Controller\AuthController',
			'Admin\Controller\Admin' => 'Admin\Controller\AdminController',

		],

	],
	
	'controller_plugins' =>
	[
		
        'invokables' =>
		[
			
            'FlashMessenger' => 'Admin\Mvc\Controller\Plugin\FlashMessenger',
			
        ],
		
    ],
	
	'view_helpers' =>
	[
		
		'invokables' =>
		[
			
			//General
			'adminFormRow' => 'Admin\View\Helper\FormRow',
			'adminFlashMessenger' => 'Admin\View\Helper\FlashMessenger',

			//Application specific
			'loggedAdmin' => 'Admin\View\Helper\LoggedAdmin',
			
		],
		
	],
	
	'view_manager' =>
	[
		
		'display_not_found_reason' => true,
		'display_exceptions' => true,
		'doctype' => 'HTML5',
		'not_found_template' => 'error/404',
		'exception_template' => 'error/index',

		'base_path' => '/admin/',
		
		'template_map' =>
		[
			
			'admin/empty' => __DIR__ . '/../view/layout/empty.phtml',
			'admin/login' => __DIR__ . '/../view/layout/login.phtml',
			'admin/layout' => __DIR__ . '/../view/layout/layout.phtml',
			'error/404' => __DIR__ . '/../view/error/404.phtml',
			'error/index' => __DIR__ . '/../view/error/index.phtml',
		
		],
		
		'template_path_stack' =>
		[
			
			__DIR__ . '/../view',
		
		],

		'layouts' => 
        [

            __NAMESPACE__ => 
            [

            	'Auth' => 'admin/login',

            	/*'Controller' =>
            	[

            		'action' => 'admin/empty',

            	],*/

                '*' => 'admin/layout'

            ],

        ],
		
	],

	'acl' => 
    [

        __NAMESPACE__ => 
        [

            'roles' =>
            [

                'guest' => null,
                'admin' => 'guest',

            ],

            'resources' =>
            [

            	'Auth' => 'guest',

            	'*' => 'admin',

            ],

            'error' => '/admin/auth/login/'

        ],

    ],

	'navigation' => 
	[

	     'Admin' => 
	     [

	         [

	         	'type' => 'Andreatta\Navigation\Page\Mvc',
	            'label' => __('Home'),
	            'route' => 'admin',
	            'controller' => 'Index',
	            'action' => 'index',
				'icon' => 'fa fa-home',

	         ],

	         [

	         	'type' => 'Andreatta\Navigation\Page\Mvc',
	            'label' => __('Admins'),
	            'route' => 'admin',
	            'controller' => 'admin',
				'icon' => 'fa fa-unlock',

	         ],

	         /*[

	         	'type' => 'Andreatta\Navigation\Page\Mvc',
				'label' => __('Nested Menu Example'),
				'route' => 'admin',
				'controller' => 'nested-menu',
				'icon' => 'fa fa-money',

				'pages' => 
				[

					[

						'type' => 'Andreatta\Navigation\Page\Mvc',
						'label' => __('Submenu 1'),
						'route' => 'admin/wildcard',
						'controller' => 'nested-menu',
						'action' => 'test',

						'params' =>
						[

							'param1' => 'test',

						]

					],

				]

	         ],*/

	     ],

	 ],

];
