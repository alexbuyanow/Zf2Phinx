<?php

namespace Zf2Phinx\Service;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ConnectionInterface;
use Zend\Db\Adapter\Driver\DriverInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Zf2PhinxServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @skip
     */
    public function testInvoke()
    {
        $dbConnectionMock = $this->getMockBuilder(ConnectionInterface::class)
            ->setMethods([])
            ->getMock();

        $dbDriverMock = $this->getMockBuilder(DriverInterface::class)
            ->setMethods([])
            ->getMock();

        $dbDriverMock
            ->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue($dbConnectionMock));

        $dbAdapterMock = $this->getMockBuilder(AdapterInterface::class)
            ->setMethods(['getDriver', 'getPlatform'])
            ->getMock();

        $dbAdapterMock
            ->expects($this->once())
            ->method('getDriver')
            ->will($this->returnValue($dbDriverMock));

        $serviceLocatorMock = $this->getMockBuilder(ServiceLocatorInterface::class)
            ->setMethods(['has', 'get'])
            ->getMock();

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
}
