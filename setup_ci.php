<?php
use Nette\Neon\Neon;

const WWW_DIR = __DIR__;
const APP_DIR = WWW_DIR . "/app";

require WWW_DIR . "/vendor/autoload.php";
Tracy\Debugger::enable(null, APP_DIR . "/log");

$filename = WWW_DIR . "/app/config/local.neon";
$cfg = Neon::decode(file_get_contents($filename));
$cfg["database"]["default"]["dsn"] = "mysql:host=mysql;dbname=heroesofabenez";
unlink($filename);
file_put_contents($filename, Neon::encode($cfg, Neon::BLOCK));

/*$container = require APP_DIR . "/bootstrap.php";
$connection = $container->getByType(\Nette\Database\Connection::class);

$sqlFiles = ["structure.sql", "data_basic.sql", "data_test.sql"];
foreach($sqlFiles as $file) {
  \Nette\Database\Helpers::loadFromFile($connection, __DIR__ . "/app/sqls/" . $file);
}*/
?>
