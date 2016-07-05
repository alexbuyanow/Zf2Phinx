<?php

namespace Zf2Phinx;

use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;

/**
 * Module class
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ConsoleUsageProviderInterface,
    ConsoleBannerProviderInterface
{
    const MODULE_NAME    = 'ZF2Phinx';
    const MODULE_VERSION = '0.1.0';

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__.'/../../config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__,
                ],
            ],
        ];
    }

    /**
     * Returns an array or a string containing usage information for this module's Console commands.
     *
     * @param  AdapterInterface  $console
     * @return array|string|null
     */
    public function getConsoleUsage(Console $console)
    {
        return [
            'Common command flags',
            ['-q',        'Do not output any message'],
            ['-n',        'Do not ask any interactive question'],
            ['-v|vv|vvv', 'Verbosity of messages: normal|more verbose|debug'],
            'Commands',
            'zf2phinx create [-t TEMPLATE] [-l CLASS] MIGRATION' => 'Create a new migration',
            ['-t TEMPLATE', 'Use an alternative template'],
            ['-l CLASS',    'Use a class implementing "Phinx\Migration\CreationInterface" to generate the template'],
            ['MIGRATION',   'Unique migration name'],
//            'help         Displays help for a command',
//            'list         Lists commands',
            'zf2phinx migrate [-t TARGET] [-d DATE] -e ENVIRONMENT' => 'Migrate the database',
            ['-t TARGET', 'The version number to migrate to. Format: YYYYMMDDHHMMSS'],
            ['-d DATE', 'The date to migrate to. Format: YYYYMMDD'],
            ['-e ENVIRONMENT', 'The target environment'],
            'zf2phinx rollback [-t TARGET] [-d DATE] -e ENVIRONMENT' => 'Rollback the last or to a specific migration',
            ['-t TARGET', 'The version number to rollback to. Format: YYYYMMDDHHMMSS'],
            ['-d DATE', 'The date to rollback to. Format: YYYYMMDD'],
            ['-e ENVIRONMENT', 'The target environment'],
            'zf2phinx status [-f FORMAT] -e ENVIRONMENT' => 'Show migration status',
            ['-f FORMAT', 'The output format: text or json. Defaults to text'],
            ['-e ENVIRONMENT', 'The target environment'],
            'zf2phinx test -e ENVIRONMENT' => 'Verify the configuration file',
            ['-e ENVIRONMENT', 'The target environment'],
        ];
    }

    /**
     * Returns a string containing a banner text, that describes the module and/or the application.
     *
     * @param  AdapterInterface $console
     * @return string|null
     */
    public function getConsoleBanner(Console $console)
    {
        return self::MODULE_NAME.' '.self::MODULE_VERSION;
    }
}
