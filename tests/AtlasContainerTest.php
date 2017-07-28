<?php
namespace Cadre\AtlasOrmDebugBarBridge;

use Aura\Sql\ConnectionLocator;
use Aura\Sql\ExtendedPdo as BaseExtendedPdo;
use PHPUnit\Framework\TestCase;
use DebugBar\DataCollector\PDO\TraceablePDO;
use PDO;

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

    public function testContainerWithConnectionFactory()
    {
        $container = new AtlasContainer('sqlite::memory:');
        $factory = new ConnectionFactory('sqlite::memory:');
        $container->setReadConnection('readonly', $factory);

        $locator = $container->getConnectionLocator();
        $extended = $locator->getRead('readonly');
        $pdo = $extended->getPdo();

        // Shouldn't reconnect
        $extended->connect();

        $this->assertInstanceOf(ConnectionLocator::class, $locator);
        $this->assertInstanceOf(ExtendedPdo::class, $extended);
        $this->assertInstanceOf(TraceablePDO::class, $pdo);
    }

    public function testContainerWithPdo()
    {
        $container = new AtlasContainer(new PDO('sqlite::memory:'));
        $locator = $container->getConnectionLocator();
        $extended = $locator->getDefault();
        $pdo = $extended->getPdo();

        // Shouldn't reconnect
        $extended->connect();

        $this->assertInstanceOf(ConnectionLocator::class, $locator);
        $this->assertInstanceOf(ExtendedPdo::class, $extended);
        $this->assertInstanceOf(TraceablePDO::class, $pdo);
    }

    public function testContainerWithExtendedExtendedPdo()
    {
        $container = new AtlasContainer(new ExtendedPdo('sqlite::memory:'));
        $locator = $container->getConnectionLocator();
        $extended = $locator->getDefault();
        $pdo = $extended->getPdo();

        // Shouldn't reconnect
        $extended->connect();

        $this->assertInstanceOf(ConnectionLocator::class, $locator);
        $this->assertInstanceOf(ExtendedPdo::class, $extended);
        $this->assertInstanceOf(TraceablePDO::class, $pdo);
    }

    public function testContainerWithExtendedBaseExtendedPdo()
    {
        $container = new AtlasContainer(new BaseExtendedPdo('sqlite::memory:'));
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
