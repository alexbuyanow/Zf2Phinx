<?php

namespace Zf2Phinx\Service;

use Phinx\Config\Config;
use Phinx\Console\PhinxApplication;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Phinx service factory
 */
class Zf2PhinxServiceFactory
{
    use ServiceLocatorProviderTrait;

    /**
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Zf2PhinxService
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $this->getServiceLocator($serviceLocator);

        $service = new Zf2PhinxService(
            $this->getPhinxApplication(),
            $this->getConfig($serviceLocator)
        );

        return $service;
    }

    /**
     * Get Phinx application
     *
     * @return PhinxApplication
     */
    private function getPhinxApplication()
    {
        return new PhinxApplication();
    }

    /**
     * Gets Phinx config
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Config
     */
    private function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!(array_key_exists('zf2phinx', $config) && is_array($config['zf2phinx']))) {
            throw new Exception\RuntimeException('Zf2Phinx config is not found');
        }

        return new Config($this->performConfig($serviceLocator, $config['zf2phinx']));
    }

    /**
     * Performs config array from ZF to Phinx structure
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @param  array                   $config
     * @return array
     */
    private function performConfig(ServiceLocatorInterface $serviceLocator, array $config)
    {
        if (!(array_key_exists('environments', $config) && is_array($config['environments']))) {
            throw new Exception\RuntimeException('Zf2Phinx environment config is not found');
        }

        array_walk(
            $config['environments'],
            function (&$element, $key) use ($serviceLocator) {
                if (is_array($element) && array_key_exists('db_adapter', $element)) {
                    if (!$serviceLocator->has($element['db_adapter'])) {
                        $message = sprintf(
                            'Adapter for environment %s is not found',
                            $key
                        );
                        throw new Exception\RuntimeException($message);
                    }

                    $adapter = $serviceLocator->get($element['db_adapter']);

                    if (!$adapter instanceof AdapterInterface) {
                        $message = sprintf(
                            'Adapter for environment %s must implement %s; %s given',
                            $key,
                            AdapterInterface::class,
                            is_object($adapter) ? get_class($adapter) : gettype($adapter)
                        );
                        throw new Exception\RuntimeException($message);
                    }

                    $connection = $adapter
                        ->getDriver()
                        ->getConnection();

                    $element['connection'] = $connection->getResource();
                    $element['name']       = $connection->getCurrentSchema();
                }

                return $element;
            }
        );

        return $config;
    }
}
