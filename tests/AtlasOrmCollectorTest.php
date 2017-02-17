<?php
namespace Cadre\AtlasOrmDebugBarBridge;

use Aura\Sql\ConnectionLocator;
use PHPUnit\Framework\TestCase;
use DebugBar\DataCollector\PDO\TraceablePDO;

class AtlasOrmCollectorTest extends TestCase
{
    public function testCollectorWithNoDefaultConnection()
    {
        $collector = new AtlasOrmCollector();
        $collections = $collector->getConnections();

        $this->assertArrayNotHasKey('default', $collections);
    }

    public function testCollectorWithDefaultConnection()
    {
        $container = $this->createMock(AtlasContainer::class);
        $locator = $this->createMock(ConnectionLocator::class);
        $extended = $this->createMock(ExtendedPdo::class);
        $pdo = $this->createMock(TraceablePDO::class);

        $extended->method('getPdo')->willReturn($pdo);
        $locator->method('getDefault')->willReturn($extended);
        $container->method('getConnectionLocator')->willReturn($locator);

        $collector = new AtlasOrmCollector($container);
        $collections = $collector->getConnections();

        $this->assertArrayHasKey('default', $collections);
        $this->assertSame($pdo, $collections['default']);
    }

    public function testCollectorWithConnectionFactory()
    {
        $container = new AtlasContainer('sqlite::memory:');
        $factory = new ConnectionFactory('sqlite::memory:');
        $container->setReadConnection('readonly', $factory);

        $collector = new AtlasOrmCollector($container);
        $collector->addConnectionFactory($factory, 'readonly');

        $collections = $collector->getConnections();

        $this->assertArrayHasKey('default', $collections);
        $this->assertArrayNotHasKey('readonly', $collections);

        $extended = $container->getConnectionLocator()->getRead('readonly');

        $collections = $collector->getConnections();
        $this->assertArrayHasKey('default', $collections);
        $this->assertArrayHasKey('readonly', $collections);
        $this->assertNotSame($collections['default'], $collections['readonly']);
    }
}
