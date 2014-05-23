<?php

return
[

    'doctrine' => 
    [

        'connection' => 
        [

            'orm_default' =>
            [

                'configuration' => 'orm_default',
                'eventmanager'  => 'orm_default',
                'driverClass'   => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                'params' => 
                [

                    'memory' => true,

                ],
                
            ],

        ],

        'driver' =>
        [
            
            'AndreattaTest_driver' =>
            [
                
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/AndreattaTest/Mock/Entity'],
                
            ],
            
            'orm_default' =>
            [
                
                'drivers' =>
                [
                    
                    'AndreattaTest\Mock\Entity' => 'AndreattaTest_driver'
                    
                ],
                
            ]
            
        ],

    ],

];