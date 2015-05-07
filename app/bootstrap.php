<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") { exit; }
require LIBS_DIR . '/nette.phar';
Tracy\Debugger::enable(null, APP_DIR . '/log');

date_default_timezone_set("Europe/Prague");

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(APP_DIR . "/temp");
$configurator->addConfig(APP_DIR . '/config.neon');
$configurator->createRobotLoader()
    ->addDirectory(LIBS_DIR)
    ->addDirectory(APP_DIR)
    ->register();
$container = $configurator->createContainer();

use Nette\Application\Routers\RouteList,
    Nette\Application\Routers\Route;

$router = new RouteList;
$router[] = new Route("profile/<id>", "Profile:view");
$router[] = new Route("guild/join[/<id>]", "Guild:join");
$router[] = new Route("guild/create", "Guild:create");
$router[] = new Route("guild/<id>", "Guild:view");
$router[] = new Route("travel[/<location>]", "Travel:default");
$router[] = new Route("<presenter>[/<action>][/<id>]", "Homepage:default");
$container->addService("router", $router);

$container->getService("application")->run();
?>