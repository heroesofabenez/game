<?php
const WWW_DIR = __DIR__;
const APP_DIR = WWW_DIR . "/app";

require WWW_DIR . "/vendor/autoload.php";
Tracy\Debugger::enable(null, APP_DIR . "/log");

$container = require APP_DIR . "/bootstrap.php";
$user = $container->getService("security.user");
if(!$user->isLoggedIn()) $user->login();
if($user->id === 0) {
  echo "Cannot login. Exiting.";
  exit(255);
}
$result = $container->getService("mytester.runner")->execute();
exit((int) $result);
?>