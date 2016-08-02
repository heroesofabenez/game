<?php
const WWW_DIR = __DIR__;
const APP_DIR = WWW_DIR . "/app";

$container = require APP_DIR . "/bootstrap.php";
$container->getService("application")->run();
?>
