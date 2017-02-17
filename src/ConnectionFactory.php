<?php
namespace Cadre\AtlasOrmDebugBarBridge;

use Aura\Sql\ExtendedPdo as BaseExtendedPdo;
use DebugBar\DataCollector\PDO\TraceablePDO;
use PDO;

class ConnectionFactory
{
    private $dsn;
    private $username;
    private $password;
    private $options = array();
    private $attributes = array();
    private $pdo;

    public function __construct(
        $dsn,
        $username = null,
        $password = null,
        array $options = array(),
        array $attributes = array()
    ) {
        if ($dsn instanceof ExtendedPdo) {
            $this->pdo = $dsn;
        } elseif ($dsn instanceof BaseExtendedPdo) {
            $this->pdo = new ExtendedPdo(new TraceablePDO($dsn->getPdo()));
        } elseif ($dsn instanceof TraceablePDO) {
            $this->pdo = new ExtendedPdo($dsn);
        } elseif ($dsn instanceof PDO) {
            $this->pdo = new ExtendedPdo(new TraceablePDO($dsn));
        } else {
            $this->dsn = $dsn;
            $this->username = $username;
            $this->password = $password;
            $this->options = $options;
            $this->attributes = array_replace($this->attributes, $attributes);
        }
    }

    public function __invoke()
    {
        if (!isset($this->pdo)) {
            $this->pdo = new ExtendedPdo(
                $this->dsn,
                $this->username,
                $this->password,
                $this->options,
                $this->attributes
            );
        }

        if (isset($this->collector)) {
            $this->collector->addConnection($this->pdo->getPdo(), $this->name);
        }

        return $this->pdo;
    }

    public function setCollector(AtlasOrmCollector $collector, $name = null)
    {
        $this->collector = $collector;
        $this->name = $name;
    }
}
