<?php
use Nette\Application\Routers\RouteList,
    Nette\Application\Routers\Route;

require WWW_DIR . "/vendor/autoload.php";
Tracy\Debugger::enable(null, APP_DIR . "/log");

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(APP_DIR . "/temp");
$configurator->addConfig(APP_DIR . "/config/main.neon");
$configurator->createRobotLoader()
    ->addDirectory(APP_DIR)
    ->register();
$container = $configurator->createContainer();

$router = new RouteList;
$router[] = new Route("ranking[/<action>][/<page=1 [0-9]+>]", "Ranking:characters");
$router[] = new Route("map[/<action>]", "Map:local");
$router[] = new Route("tavern[/<action>]", "Tavern:local");
$router[] = new Route("postoffice", "Postoffice:received");
$router[] = new Route("<presenter>/<id [0-9]+>", "Homepage:view");
$router[] = new Route("<presenter>[/<action>][/<id>]", "Homepage:default");
$container->addService("router", $router);

return $container;
?>