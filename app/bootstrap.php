<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") { exit; }
require LIBS_DIR . '/nette.phar';
Tracy\Debugger::enable();

date_default_timezone_set("Europe/Prague");

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(APP_DIR . "/temp");
$configurator->addConfig(APP_DIR . '/config.neon');
$configurator->createRobotLoader()
    ->addDirectory(LIBS_DIR)
    ->register();

Game::run($configurator);
?>