<?php
define("WWW_DIR", dirname(__FILE__));
define("APP_DIR", WWW_DIR . "/app");

require __DIR__ . "/vendor/autoload.php";
Tracy\Debugger::enable(null, APP_DIR . "/log");

$container = require APP_DIR . "/bootstrap.php";
$container->getService("mytester.runner")->execute();
?>
