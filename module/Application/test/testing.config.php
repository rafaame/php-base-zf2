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
                'driverClass' =>'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array
                (
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => 'dmssmddmssmd',
                    'dbname'   => 'personalsushizf2-test',
                )/*
                'driverClass'   => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                'params' => 
                [

                    'memory' => true,

                ],*/
                
            ],

        ],

    ],

];