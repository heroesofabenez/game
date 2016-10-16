<?php
use Nette\Neon\Neon;

const WWW_DIR = __DIR__ . "/..";
const APP_DIR = WWW_DIR . "/app";

require WWW_DIR . "/vendor/autoload.php";

$config = Neon::decode(file_get_contents(APP_DIR . "/config/ci.neon"));
$dbConfig = $config["database"]["default"];

$connection = new \Nette\Database\Connection($dbConfig["dsn"], $dbConfig["user"], $dbConfig["password"]);
$sqlsFolder = APP_DIR . "/sqls";
$files = ["structure", "data_basic", "data_test"];
foreach($files as $file) {
  Nette\Database\Helpers::loadFromFile($connection, "$sqlsFolder/$file.sql");
}
?>