<?php

namespace Zf2Phinx\Controller;

use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zf2Phinx\Module;
use Zf2Phinx\Service\Zf2PhinxService;

/**
 * Controller factory test
 */
class PhinxControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests main logic of __invoke method
     *
     * @return void
     */
    public function testInvoke()
    {
        $serviceMock = $this->getMockBuilder(Zf2PhinxService::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $consoleAdapterMock = $this->getMockBuilder(AdapterInterface::class)
            ->setMethods([])
            ->getMock();

        $moduleMock = $this->getMockBuilder(Module::class)
            ->setMethods(['getConsoleUsage'])
            ->getMock();

        $moduleMock
            ->expects($this->once())
            ->method('getConsoleUsage')
            ->will($this->returnValue('Test string'));

        $moduleManagerMock = $this->getMockBuilder(ModuleManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getModule'])
            ->getMock();

        $moduleManagerMock
            ->expects($this->once())
            ->method('getModule')
            ->with('Zf2Phinx')
            ->will($this->returnValue($moduleMock));

        $serviceLocatorMock = $this->getMockBuilder(ServiceLocatorInterface::class)
            ->setMethods(['has', 'get', 'build'])
            ->getMock();

        $serviceLocatorMock
            ->expects($this->exactly(3))
            ->method('get')
            ->will(
                $this->returnValueMap(
                    [
                        [Zf2PhinxService::class, $serviceMock],
                        ['Console', $consoleAdapterMock],
                        ['ModuleManager', $moduleManagerMock],
                    ]
                )
            );

        $factory = new PhinxControllerFactory();

        $this->assertInstanceOf(PhinxController::class, $factory($serviceLocatorMock));
    }
}
