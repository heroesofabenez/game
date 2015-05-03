<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") { exit; }
require LIBS_DIR . '/Nette/nette.phar';
use Tracy\Debugger;
use Nette\Database\Connection;
Debugger::enable();

$libraries = array("config_ini", "html", "user", "character", "pet", "combat", "game");
foreach($libraries as $lib) {
  require LIBS_DIR . "/$lib.php";
}

$configObj = new Config_Ini();
$config = $configObj->getConfig();

date_default_timezone_set("Europe/Prague");

session_start();
session_save_path(realpath(APP_DIR . '/temp'));

$page = new Page;
$page->addMeta("content-type", "text/html; charset=utf-8");
//$page->attachStyle("$base_url/style.css");
//$page->attachScript("http://code.jquery.com/jquery-latest.pack.js");

$db_ = $config["database"];
$conn = new Connection($db_["dns"], $db_["username"], $db_["password"]);
unset($db_);
$user = new GUser();
$user->reloadData();

$game = Game::Init($conn, $page, $user, $config);
$game->run();
?>