<?php
const WWW_DIR = __DIR__;
const APP_DIR = WWW_DIR . "/app";

require WWW_DIR . "/vendor/autoload.php";
Tracy\Debugger::enable(null, APP_DIR . "/log");

$container = require APP_DIR . "/bootstrap.php";
$result = $container->getService("mytester.runner")->execute();
exit((int) $result);
?>
