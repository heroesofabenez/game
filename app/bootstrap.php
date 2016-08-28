<?php
require WWW_DIR . "/vendor/autoload.php";
Tracy\Debugger::enable(null, APP_DIR . "/log");

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(APP_DIR . "/temp");
$configurator->addConfig(APP_DIR . "/config/main.neon");
if($configurator->isDebugMode()) $configurator->addConfig(APP_DIR . "/config/mytester.neon");
$container = $configurator->createContainer();

return $container;
?>