<?php

namespace Zf2Phinx\Service;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ConnectionInterface;
use Zend\Db\Adapter\Driver\DriverInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zf2Phinx\Service\Exception\RuntimeException;

/**
 * Service factory unit test
 */
class Zf2PhinxServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests main logic of __invoke method
     *
     * @return void
     */
    public function testInvoke()
    {
        $dbConnectionMock = $this
            ->getMockBuilder(ConnectionInterface::class)
            ->setMethods([])
            ->getMock();

        $dbDriverMock = $this
            ->getMockBuilder(DriverInterface::class)
            ->setMethods([])
            ->getMock();

        $dbDriverMock
            ->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue($dbConnectionMock));

        $dbAdapterMock = $this
            ->getMockBuilder(AdapterInterface::class)
            ->setMethods(['getDriver', 'getPlatform'])
            ->getMock();

        $dbAdapterMock
            ->expects($this->once())
            ->method('getDriver')
            ->will($this->returnValue($dbDriverMock));

        $serviceLocatorMock = $this->getServiceLocatorMock();

        $serviceLocatorMock
            ->expects($this->exactly(2))
            ->method('get')
            ->will(
                $this->returnValueMap(
                    [
                        ['Config', ['zf2phinx' => ['environments' => ['test' => ['db_adapter' => 'AdapterName']]]]],
                        ['AdapterName', $dbAdapterMock],
                    ]
                )
            );

        $serviceLocatorMock
            ->expects($this->once())
            ->method('has')
            ->with('AdapterName')
            ->will($this->returnValue(true));

        $factory = new Zf2PhinxServiceFactory();

        $this->assertInstanceOf(Zf2PhinxService::class, $factory($serviceLocatorMock));
    }

    /**
     * Tests module config is not found exception
     *
     * @return void
     */
    public function testInvokeConfigNotFoundException()
    {
        $serviceLocatorMock = $this->getServiceLocatorMock();

        $serviceLocatorMock
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->willReturn($this->anything());

        $this->setExpectedException(
            RuntimeException::class,
            'Zf2Phinx config is not found'
        );

        $factory = new Zf2PhinxServiceFactory();
        $factory($serviceLocatorMock);
    }

    /**
     * Tests environment is not found in config exception
     *
     * @return void
     */
    public function testInvokeEnvironmentConfigNotFoundException()
    {
        $serviceLocatorMock = $this->getServiceLocatorMock();

        $serviceLocatorMock
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->willReturn(['zf2phinx' => []]);

        $this->setExpectedException(
            RuntimeException::class,
            'Zf2Phinx environment config is not found'
        );

        $factory = new Zf2PhinxServiceFactory();
        $factory($serviceLocatorMock);
    }

    /**
     * Tests incorrect DB adapter in config exception
     *
     * @return void
     */
    public function testInvokeEnvironmentAdapterNotFoundException()
    {
        $serviceLocatorMock = $this->getServiceLocatorMock();

        $serviceLocatorMock
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->willReturn(['zf2phinx' => ['environments' => ['test' => ['db_adapter' => $this->anything()]]]]);

        $this->setExpectedException(
            RuntimeException::class,
            'Adapter for environment test is not found'
        );

        $factory = new Zf2PhinxServiceFactory();
        $factory($serviceLocatorMock);
    }

    /**
     * Tests DB Adapter is not implement interface exception
     *
     * @return void
     */
    public function testInvokeEnvironmentAdapterIsNotInstanceOfAdapterInterfaceException()
    {
        $serviceLocatorMock = $this->getServiceLocatorMock();

        $serviceLocatorMock
            ->expects($this->exactly(2))
            ->method('get')
            ->will(
                $this->returnValueMap(
                    [
                        ['Config', ['zf2phinx' => ['environments' => ['test' => ['db_adapter' => 'AdapterName']]]]],
                        ['AdapterName', $this->anything()],
                    ]
                )
            );

        $serviceLocatorMock
            ->expects($this->once())
            ->method('has')
            ->with('AdapterName')
            ->will($this->returnValue(true));

        $this->setExpectedException(
            RuntimeException::class,
            'Adapter for environment test must implement Zend\Db\Adapter\AdapterInterface; PHPUnit_Framework_Constraint_IsAnything given'
        );

        $factory = new Zf2PhinxServiceFactory();
        $factory($serviceLocatorMock);
    }

    /**
     * Gets service locator mock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getServiceLocatorMock()
    {
        return $this
            ->getMockBuilder(ServiceLocatorInterface::class)
            ->setMethods(['has', 'get'])
            ->getMock();
    }
}
