<?php
declare(strict_types=1);

namespace Cadre\AtlasOrmDebugBarBridge;

use Aura\Sql\ConnectionLocator;
use PHPUnit\Framework\TestCase;
use DebugBar\DataCollector\PDO\TraceablePDO;

class AtlasContainerTest extends TestCase
{
    public function testContainer()
    {
        $container = new AtlasContainer('sqlite::memory:');
        $locator = $container->getConnectionLocator();
        $extended = $locator->getDefault();
        $pdo = $extended->getPdo();

        // Shouldn't reconnect
        $extended->connect();

        $this->assertInstanceOf(ConnectionLocator::class, $locator);
        $this->assertInstanceOf(ExtendedPdo::class, $extended);
        $this->assertInstanceOf(TraceablePDO::class, $pdo);
    }
}
