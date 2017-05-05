<?php
require __DIR__ . "/vendor/autoload.php";
if(PHP_SAPI != "cli") {
  Tracy\Debugger::enable(null, __DIR__ . "/log");
} else {
  $_SERVER["SERVER_NAME"] = "hoa.local";
}

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(__DIR__ . "/temp");
$configurator->addConfig(__DIR__ . "/app/config/main.neon");
$configurator->addConfig(__DIR__ . "/app/config/ci.neon");
$configurator->addConfig(__DIR__ . "/app/config/mytester.neon");
$configurator->addParameters([
  "appDir" => __DIR__ . "/app",
]);
$container = $configurator->createContainer();

$user = $container->getService("security.user");
if(!$user->isLoggedIn()) {
  $user->login();
}
if($user->id === 0) {
  echo "Cannot login. Exiting.";
  exit(255);
}

$result = $container->getService("mytester.runner")->execute();
exit((int) $result);
?>