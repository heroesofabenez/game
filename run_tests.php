<?php
const WWW_DIR = __DIR__;
const APP_DIR = WWW_DIR . "/app";

require WWW_DIR . "/vendor/autoload.php";
if(PHP_SAPI != "cli") {
  Tracy\Debugger::enable(null, WWW_DIR . "/log");
} else {
  $_SERVER["SERVER_NAME"] = "hoa.local";
}

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(WWW_DIR . "/temp");
$configurator->addConfig(APP_DIR . "/config/ci.neon");
$configurator->addParameters([
  "appDir" => APP_DIR,
]);
$container = $configurator->createContainer();

$user = $container->getService("security.user");
if(!$user->isLoggedIn()) $user->login();
if($user->id === 0) {
  echo "Cannot login. Exiting.";
  exit(255);
}

$result = $container->getService("mytester.runner")->execute();
exit((int) $result);
?>