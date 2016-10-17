<?php
declare(strict_types=1);

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
}
