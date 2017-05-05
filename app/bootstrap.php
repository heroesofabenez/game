<?php
declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";
Tracy\Debugger::enable(null, __DIR__ . "/../log");

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(__DIR__ . "/../temp");
$configurator->addConfig(__DIR__ . "/config/main.neon");
$configurator->addConfig(__DIR__ . "/config/local.neon");
if($configurator->isDebugMode()) {
  $configurator->addConfig(__DIR__ . "/config/mytester.neon");
}
$container = $configurator->createContainer();

return $container;
?>