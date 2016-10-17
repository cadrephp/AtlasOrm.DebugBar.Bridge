<?php
namespace Cadre\AtlasOrmDebugBarBridge;

use Aura\Sql\ExtendedPdo as BaseExtendedPdo;
use DebugBar\DataCollector\PDO\TraceablePDO;
use PDO;
use PHPUnit\Framework\TestCase;

class ConnectionFactoryTest extends TestCase
{
    public function testConnectionFactoryWithConfig()
    {
        $factory = new ConnectionFactory('sqlite::memory:');
        $extended = ($factory)();

        $this->assertInstanceOf(ExtendedPdo::class, $extended);
    }

    public function testConnectionFactoryWithExtendedPdo()
    {
        $pdo = new ExtendedPdo('sqlite::memory:');

        $factory = new ConnectionFactory($pdo);
        $extended = ($factory)();

        $this->assertInstanceOf(ExtendedPdo::class, $extended);
        $this->assertSame($pdo, $extended);
    }

    public function testConnectionFactoryWithTraceablePdo()
    {
        $pdo = new TraceablePDO(new PDO('sqlite::memory:'));

        $factory = new ConnectionFactory($pdo);
        $extended = ($factory)();

        $this->assertInstanceOf(ExtendedPdo::class, $extended);
        $this->assertSame($pdo, $extended->getPdo());
    }

    public function testConnectionFactoryWithPdo()
    {
        $pdo = new PDO('sqlite::memory:');

        $factory = new ConnectionFactory($pdo);
        $extended = ($factory)();
        $traceable = $extended->getPdo();

        $this->assertInstanceOf(ExtendedPdo::class, $extended);
        $this->assertInstanceOf(TraceablePDO::class, $traceable);
    }

    public function testConnectionFactoryWithBaseExtendedPdo()
    {
        $pdo = new BaseExtendedPdo('sqlite::memory:');

        $factory = new ConnectionFactory($pdo);
        $extended = ($factory)();
        $traceable = $extended->getPdo();

        $this->assertInstanceOf(ExtendedPdo::class, $extended);
        $this->assertInstanceOf(TraceablePDO::class, $traceable);
    }
}
