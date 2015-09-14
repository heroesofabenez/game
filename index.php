<?php
define("WWW_DIR", dirname(__FILE__));
define("APP_DIR", WWW_DIR . "/app");

$container = require APP_DIR . "/bootstrap.php";
$container->getService("application")->run();
?>