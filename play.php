<?php
define('MASTER_ID', 'HEROES_OF_ABENEZ');
$base_url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["SCRIPT_NAME"];

define('WWW_DIR', dirname(__FILE__));
define('APP_DIR', WWW_DIR . '/app');
define('LIBS_DIR', WWW_DIR . '/libs');

require APP_DIR . '/bootstrap.php';
?>