<?php

namespace Zf2Phinx\Controller;

use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zf2Phinx\Module;
use Zf2Phinx\Service\ServiceLocatorProviderTrait;
use Zf2Phinx\Service\Zf2PhinxService;

/**
 * Phinx controller factory
 */
class PhinxControllerFactory
{
    use ServiceLocatorProviderTrait;

    /**
     * @param  ServiceLocatorInterface $serviceLocator
     * @return PhinxController
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $this->getServiceLocator($serviceLocator);
        $console        = $this->getConsole($serviceLocator);

        return new PhinxController(
            $this->getZf2PhinxService($serviceLocator),
            $console,
            $this->getModule($this->getModuleManager($serviceLocator))
                ->getConsoleUsage($console)
        );
    }

    /**
     * Gets ZF Phinx Service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Zf2PhinxService
     */
    private function getZf2PhinxService(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get(Zf2PhinxService::class);
    }

    /**
     * Gets console adapter
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return AdapterInterface
     */
    private function getConsole(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('Console');
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ModuleManager
     */
    private function getModuleManager(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('ModuleManager');
    }

    /**
     * @param ModuleManager $moduleManager
     * @return Module
     */
    private function getModule(ModuleManager $moduleManager)
    {
        return $moduleManager->getModule('Zf2Phinx');
    }
}
