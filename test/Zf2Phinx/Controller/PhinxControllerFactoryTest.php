<?php

namespace Zf2Phinx\Controller;

use Zend\Console\Adapter\AdapterInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zf2Phinx\Service\Zf2PhinxService;

class PhinxControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInvoke()
    {
        $serviceMock = $this->getMockBuilder(Zf2PhinxService::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $consoleAdapterMock = $this->getMockBuilder(AdapterInterface::class)
            ->setMethods([])
            ->getMock();

        $serviceLocatorMock = $this->getMockBuilder(ServiceLocatorInterface::class)
            ->setMethods(['has', 'get', 'build'])
            ->getMock();

        $serviceLocatorMock
            ->expects($this->exactly(2))
            ->method('get')
            ->will(
                $this->returnValueMap(
                    [
                        [Zf2PhinxService::class, $serviceMock],
                        ['Console', $consoleAdapterMock],
                    ]
                )
            );

        $factory = new PhinxControllerFactory();

        $this->assertInstanceOf(PhinxController::class, $factory($serviceLocatorMock));
    }
}
