<?php
namespace Cadre\AtlasOrmDebugBarBridge;

use Atlas\Orm\AtlasContainer as BaseAtlasContainer;
use Aura\Sql\ConnectionLocator;

class AtlasContainer extends BaseAtlasContainer
{
    protected function setConnectionLocator(array $args)
    {
        switch (true) {

            case $args[0] instanceof ExtendedPdo:
                $extendedPdo = $args[0];
                $default = function () use ($extendedPdo) {
                    return $extendedPdo;
                };
                $driver = $extendedPdo->getAttribute(PDO::ATTR_DRIVER_NAME);
                break;

            case $args[0] instanceof PDO:
                $extendedPdo = $args[0];
                $default = function () use ($pdo) {
                    return new ExtendedPdo($pdo);
                };
                $driver = $pdo->getAttribute(ExtendedPdo::ATTR_DRIVER_NAME);
                break;

            default:
                $default = function () use ($args) {
                    return new ExtendedPdo(...$args);
                };
                $parts = explode(':', $args[0]);
                $driver = array_shift($parts);
                break;
        }

        $this->connectionLocator = new ConnectionLocator($default);
        return $driver;
    }
}
