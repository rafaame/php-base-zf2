<?php

namespace Application;

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
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
                
            ],
            
            'orm_default' =>
            [
                
                'drivers' =>
                [
                    
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                    
                ],
                
            ],
            
        ],
        
        /*'authentication' =>
        [
            
            'application' =>
            [
                
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Application\Entity\Customer',
                'identity_property' => 'email',
                'credential_property' => 'password',
                'credentialCallable' => 'Application\Entity\Customer::hashPassword',
                'storage' => 'Application\Auth\Storage\Session',
                
            ],
            
        ],

        'authenticationadapter' =>
        [

            'application' => true,

        ],

        'authenticationstorage' =>
        [

            'application' => true,

        ],

        'authenticationservice' =>
        [

            'application' => true,
        ]*/
        
    ],
    
	'router' =>
	[
		
		'routes' =>
		[
			
			'application' =>
			[
				
				'type' => 'Segment',
				'options' =>
				[
					
					'route' => '[/:controller[/:action]][/]',
					
					'constraints' =>
					[

						'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',

					],
					
					'defaults' =>
					[

						'__NAMESPACE__' => 'Application\Controller',
						'module' => 'Application',
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
				
			],

            /*'application\about-us' =>
            [

                'type' => 'Segment',
                'options' =>
                [

                    'route' => '/about-us[/]',
                    'defaults' =>
                    [

                        '__NAMESPACE__' => 'Application\Controller',
                        'module' => 'Application',
                        'controller' => 'Index',
                        'action' => 'about-us',

                    ],

                ],

            ],*/
			
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

            'Application\Navigation' => 'Application\Navigation\Service\ApplicationNavigationFactory',

            'Application\Auth' => function($sm)
            {

                return $sm->get('doctrine.authenticationservice.application');
                
            },

            'Application\Auth\Storage\Session' => function($sm)
            {

                return new \Zend\Authentication\Storage\Session('Application\Auth');

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

    'translator' =>
    [

        'locale' => 'en_US',

        'translation_file_patterns' =>
        [

            [

                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',

            ],

        ],

    ],

    'controllers' =>
    [

        'invokables' =>
        [

            'Application\Controller\Index' => 'Application\Controller\IndexController',

        ],
        
    ],

    'controller_plugins' =>
    [

        'invokables' =>
        [

            'applicationSession' => 'Application\Mvc\Controller\Plugin\ApplicationSession',

        ],

    ],

    'view_helpers' => 
    [
        
        'invokables' =>
        [
            
            //General
            'formRow' => 'Application\View\Helper\FormRow',
            'flashMessenger' => 'Application\View\Helper\FlashMessenger',

            //'loggedCustomer' => 'Application\View\Helper\LoggedCustomer',

        ],
        
    ],

    'view_manager' =>
    [

        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',

        'template_map' =>
        [

            'application/layout' => __DIR__ . '/../view/layout/layout.phtml',
            //'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',

        ],

        'template_path_stack' =>
        [

            __DIR__ . '/../view',

        ],

        'layout' => 'application/layout',

        'layouts' =>
        [

            __NAMESPACE__ =>
            [

                '*' => 'application/layout'

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
                'customer' => 'guest',

            ],

            'resources' =>
            [

                '*' => 'guest',

            ],

            'error' => '/error/'

        ]

    ],

    'navigation' => 
    [

         'Application' => 
         [

             [

                'type' => 'Andreatta\Navigation\Page\Mvc',
                'label' => __('Home'),
                'route' => 'application',

             ],

         ],

     ],

    'php_settings' =>
    [

        'date.timezone' => 'America/Sao_Paulo',

    ],

];
