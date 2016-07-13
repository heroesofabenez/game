<?php
define("WWW_DIR", dirname(__FILE__));
define("APP_DIR", WWW_DIR . "/app");
define("TESTER_DIR", WWW_DIR . "/libs/mytester/src");

require __DIR__ . "/vendor/autoload.php";
Tracy\Debugger::enable(null, APP_DIR . "/log");

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(APP_DIR . "/temp");
$configurator->addConfig(APP_DIR . "/config/main.neon");
$configurator->addConfig(APP_DIR . "/config/mytester.neon");
$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(APP_DIR);
$robotLoader->register();
$container = $configurator->createContainer();
$container->getService("mytester.runner")->execute();
?>
