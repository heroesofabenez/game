<?php
define("WWW_DIR", dirname(__FILE__));
define("APP_DIR", WWW_DIR . "/app");
define("LIBS_DIR", WWW_DIR . "/libs");

$container = require APP_DIR . "/bootstrap.php";
$container->getService("application")->run();
?>