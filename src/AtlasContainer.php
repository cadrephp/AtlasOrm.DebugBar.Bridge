<?php
namespace Cadre\AtlasOrmDebugBarBridge;

use Atlas\Orm\AtlasContainer as BaseAtlasContainer;
use Aura\Sql\ConnectionLocator;

class AtlasContainer extends BaseAtlasContainer
{
    protected function setConnectionLocator(array $args)
    {
        $this->connectionLocator = new ConnectionLocator();
        $this->connectionLocator->setDefault(function () use ($args) {
            return new ExtendedPdo(...$args);
        });
    }
}
