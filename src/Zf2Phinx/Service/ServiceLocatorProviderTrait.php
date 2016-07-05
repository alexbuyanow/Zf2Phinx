<?php

namespace Zf2Phinx\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Trait for service locator getting
 */
trait ServiceLocatorProviderTrait
{
    /**
     * Gets service locator
     * 
     * @param  ServiceLocatorInterface $serviceLocator
     * @return ServiceLocatorInterface
     */
    protected function getServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        if($serviceLocator instanceof ServiceLocatorAwareInterface)
        {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }
        
        return $serviceLocator;
    }
}