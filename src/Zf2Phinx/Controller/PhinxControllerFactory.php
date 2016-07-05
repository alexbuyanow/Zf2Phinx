<?php

namespace Zf2Phinx\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
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

        return new PhinxController(
            $this->getZf2PhinxService($serviceLocator)
        );
    }

    /**
     * Gets ZF Phinx Service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Zf2PhinxService
     */
    private function getZf2PhinxService(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get(Zf2PhinxService::class);
    }

}
