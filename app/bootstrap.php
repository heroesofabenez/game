<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") { exit; }
require LIBS_DIR . '/Nette/nette.phar';
use Tracy\Debugger;
Debugger::enable(Debugger::DEVELOPMENT);

$libraries = array("config_ini", "db", "html", "user", "character", "pet"/*, "rpgclub", "base_facebook", "facebook"*/);
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

$conn = db_connect($config["database"]);
$user = new GUser();
$user->reloadData();

/*$facebook = new Facebook(array(
  'appId'  => '88540593543',
  'secret' => '027660bf6a4c47a8393e908599ab928a',
));
$user = $facebook->getUser();
if ($user) {
  try {
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $statusUrl = $facebook->getLoginStatusUrl();
  $loginUrl = $facebook->getLoginUrl();
}*/

//$club = RPGClub::Init($conn, $page, $user, $config);
//$club->run();
?>