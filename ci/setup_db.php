<?php
declare(strict_types=1);

use Nette\Neon\Neon;
use Nextras\Dbal\Utils\FileImporter;

require __DIR__ . "/../vendor/autoload.php";

Tracy\Debugger::timer("setup_db");

$config = Neon::decode(file_get_contents(__DIR__ . "/../tests/local.neon"));

$connection = new Nextras\Dbal\Connection($config["dbal"]);
$sqlsFolder = __DIR__ . "/../app/sqls";
$files = ["structure", "data_basic", "data_test"];
foreach($files as $file) {
  echo "Executing file: $file.sql ... ";
  Tracy\Debugger::timer($file);
  FileImporter::executeFile($connection, "$sqlsFolder/$file.sql");
  $time = round(Tracy\Debugger::timer($file), 2);
  echo "Done in $time second(s)\n";
}

$time = round(Tracy\Debugger::timer("setup_db"), 2);
echo "\nTotal time: $time second(s)\n";
?>