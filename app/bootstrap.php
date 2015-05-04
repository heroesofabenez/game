<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") { exit; }
require LIBS_DIR . '/Nette/nette.phar';
use Tracy\Debugger;
use Nette\Database\Connection;
Debugger::enable();

//$libraries = array("config_ini", "html", "user", "character", "pet", "combat", "game");
//foreach($libraries as $lib) {
  //require LIBS_DIR . "/$lib.php";
//}

date_default_timezone_set("Europe/Prague");

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(APP_DIR . "/temp");
$configurator->addConfig(APP_DIR . '/config.neon');
$configurator->createRobotLoader()
    ->addDirectory(LIBS_DIR)
    ->register();

$container = $configurator->createContainer();
$httpRequest = $container->getByType('Nette\Http\Request');
$uri = $httpRequest->getUrl();
$base_url = $uri->hostUrl . $uri->path;
unset($httpRequest, $uri);

$page = new Page;
$page->addMeta("content-type", "text/html; charset=utf-8");
//$page->attachStyle("$base_url/style.css");
//$page->attachScript("http://code.jquery.com/jquery-latest.pack.js");

$conn = $container->getService("nette.database.test");
$user = new GUser();
$user->reloadData();

$game = Game::Init($conn, $page, $user, $configurator);
$game->run();
?>