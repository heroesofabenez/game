<?php
use Nette\Neon\Neon;
use Nette\Utils\Finder;

const WWW_DIR = __DIR__;
const APP_DIR = WWW_DIR . "/app";

require WWW_DIR . "/vendor/autoload.php";

$filename = APP_DIR . "/config/local.neon";
$cfg = Neon::decode(file_get_contents($filename));
$cfg["database"]["default"]["dsn"] = "mysql:host=mysql;dbname=heroesofabenez";
unlink($filename);
file_put_contents($filename, Neon::encode($cfg, Neon::BLOCK));

/*$container = require APP_DIR . "/bootstrap.php";
$connection = $container->getByType(\Nette\Database\Connection::class);

$files = Finder::findFiles("*.sql")->in(WWW_DIR . "/sqls");
foreach($files as $file) {
  Nette\Database\Helpers::loadFromFile($conn, $file);
}*/
?>