<?php
use Nette\Neon\Neon;

require __DIR__ . "/../vendor/autoload.php";

$config = Neon::decode(file_get_contents(__DIR__ . "/../tests/local.neon"));
$dbConfig = $config["database"]["default"];

$connection = new \Nette\Database\Connection($dbConfig["dsn"], $dbConfig["user"], $dbConfig["password"]);
$sqlsFolder = __DIR__ . "/../app/sqls";
$files = ["structure", "data_basic", "data_test"];
foreach($files as $file) {
  Nette\Database\Helpers::loadFromFile($connection, "$sqlsFolder/$file.sql");
}
?>