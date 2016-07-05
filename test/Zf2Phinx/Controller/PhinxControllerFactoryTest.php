<?php

namespace Zf2Phinx\Controller;

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

        $serviceLocatorMock = $this->getMockBuilder(ServiceLocatorInterface::class)
            ->setMethods(['has', 'get'])
            ->getMock();

        $serviceLocatorMock
            ->expects($this->once())
            ->method('get')
            ->with(Zf2PhinxService::class)
            ->will($this->returnValue($serviceMock));

        $factory = new PhinxControllerFactory();

        $this->assertInstanceOf(PhinxController::class, $factory($serviceLocatorMock));
    }
}
