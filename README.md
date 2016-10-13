# AtlasOrm.DebugBar.Bridge

Read the instructions for using
[Atlas.Orm](https://github.com/atlasphp/Atlas.Orm) and
[PHP Debug Bar](http://phpdebugbar.com/). I'll just show you what you need
to do to make these work together.

## 1. Include Atlas.Orm and PHP Debug Bar in your project

I'm not listing either of these as a dependency of this library because I'll
let you define what version and stability you want in your project.

## 2. Use Cadre\AtlasOrmDebugBarBridge\AtlasContainer

Instead of using the normal `Atlas\Orm\AtlasContainer` use
`Cadre\AtlasOrmDebugBarBridge\AtlasContainer`.

This class functions exactly as the normal Atlas.ORM one only it uses
`Cadre\AtlasOrmDebugBarBridge\ExtendedPdo` instead of
`Aura\Sql\ExtendedPdo` which wraps the inner `PDO` object in
`DebugBar\DataCollector\PDO\TraceablePDO`.

## 3. Use Cadre\AtlasOrmDebugBarBridge\AtlasOrmCollector

I've provided `Cadre\AtlasOrmDebugBarBridge\AtlasOrmCollector` which takes
a `Cadre\AtlasOrmDebugBarBridge\AtlasContainer`, pulls the
`DebugBar\DataCollector\PDO\TraceablePDO` object out of it and passes it up
to its parent `DebugBar\DataCollector\PDO\PDOCollector`.

## Example

```php
$atlasContainer = new Cadre\AtlasOrmDebugBarBridge\AtlasContainer(
    'mysql:host=localhost;dbname=testdb',
    'username',
    'password'
);

$debugbar = new DebugBar\StandardDebugBar();
$debugbar->addCollector(
    new Cadre\AtlasOrmDebugBarBridge\AtlasOrmCollector($atlasContainer)
);
```
