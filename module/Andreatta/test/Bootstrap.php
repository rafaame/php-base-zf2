<?php

namespace AndreattaTest;

use Zend\Loader\AutoloaderFactory,
    Zend\Mvc\Service\ServiceManagerConfig,
    Zend\ServiceManager\ServiceManager,
    RuntimeException,

    AndreattaTest\Framework\TestCase;

class Bootstrap
{

    public static function init()
    {

        $zf2ModulePaths = array(dirname(dirname(__DIR__)));

        if(($path = static::findParentPath('vendor')))
            $zf2ModulePaths[] = $path;

        if(($path = static::findParentPath('module')) !== $zf2ModulePaths[0])
            $zf2ModulePaths[] = $path;

        static::initAutoloader();

        // use ModuleManager to load this module and it's dependencies
        $config =
        [

            'module_listener_options' =>
            [

                'module_paths' => $zf2ModulePaths,

                'config_glob_paths' =>
                [

                    __DIR__ . '/testing.config.php',

                ],

            ],

            'modules' => 
            [

                'DoctrineModule',
                'DoctrineORMModule',
                'ZfcBase',
                
                'Andreatta',

            ]

        ];

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();

        TestCase::setServiceManager($serviceManager);
        TestCase::setObjectManager( $serviceManager->get('Doctrine\ORM\EntityManager') );

    }

    protected static function initAutoloader()
    {
        $vendorPath = static::findParentPath('vendor');

        $zf2Path = getenv('ZF2_PATH');
        if (!$zf2Path) 
        {

            if (defined('ZF2_PATH'))
                $zf2Path = ZF2_PATH;
            elseif (is_dir($vendorPath . '/ZF2/library'))
                $zf2Path = $vendorPath . '/ZF2/library';
            elseif (is_dir($vendorPath . '/zendframework/zendframework/library'))
                $zf2Path = $vendorPath . '/zendframework/zendframework/library';

        }

        if (!$zf2Path)
            throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');

        include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        AutoloaderFactory::factory
        ([

            'Zend\Loader\StandardAutoloader' =>
            [

                'autoregister_zf' => true,
                'namespaces' =>
                [

                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,

                ],

            ],

        ]);

    }

    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) return false;
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }
}

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

if ( !isset( $_SESSION ) ) $_SESSION = [];

date_default_timezone_set('America/Sao_Paulo');

Bootstrap::init();