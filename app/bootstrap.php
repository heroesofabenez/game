<?php
require LIBS_DIR . "/nette.phar";
Tracy\Debugger::enable(null, APP_DIR . "/log");

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(APP_DIR . "/temp");
$configurator->addConfig(APP_DIR . "/config/main.neon");
$configurator->createRobotLoader()
    ->addDirectory(LIBS_DIR)
    ->addDirectory(APP_DIR)
    ->register();
$container = $configurator->createContainer();

return $container;
?>