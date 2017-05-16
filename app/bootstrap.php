<?php
declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

$configurator = new Nette\Configurator;
$configurator->enableTracy(__DIR__ . "/../log");
$configurator->setTempDirectory(__DIR__ . "/../temp");
$configurator->addConfig(__DIR__ . "/config/main.neon");
$configurator->addConfig(__DIR__ . "/config/local.neon");
$container = $configurator->createContainer();

return $container;
?>