<?php
namespace Cadre\AtlasOrmDebugBarBridge;

use DebugBar\DataCollector\PDO\PDOCollector;
use DebugBar\DataCollector\TimeDataCollector;

class AtlasOrmCollector extends PDOCollector
{
    public function __construct(
        AtlasContainer $atlasContainer = null,
        TimeDataCollector $timeCollector = null
    ) {
        if ($atlasContainer === null) {
            $pdo = null;
        } else {
            $pdo = $atlasContainer->getConnectionLocator()->getDefault()->getPdo();
        }
        parent::__construct($pdo, $timeCollector);
    }

    public function addConnectionFactory(ConnectionFactory $factory, $name = null)
    {
        $factory->setCollector($this, $name);
    }
}
