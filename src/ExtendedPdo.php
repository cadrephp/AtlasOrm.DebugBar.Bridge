<?php
namespace Cadre\AtlasOrmDebugBarBridge;

use Aura\Sql\ExtendedPdo as BaseExtendedPdo;
use DebugBar\DataCollector\PDO\TraceablePDO;
use PDO;

class ExtendedPdo extends BaseExtendedPdo
{
    public function connect()
    {
        // don't connect twice
        if ($this->pdo) {
            return;
        }

        // connect to the database
        $this->beginProfile(__FUNCTION__);
        $this->pdo = new TraceablePDO(new PDO(
            $this->dsn,
            $this->username,
            $this->password,
            $this->options
        ));
        $this->endProfile();

        // set attributes
        foreach ($this->attributes as $attribute => $value) {
            $this->setAttribute($attribute, $value);
        }
    }
}
