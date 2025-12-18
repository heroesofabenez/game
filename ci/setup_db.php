<?php
declare(strict_types=1);

use Nette\Neon\Neon;

require __DIR__ . "/../vendor/autoload.php";

Tracy\Debugger::timer("setup_db");

$filename = __DIR__ . "/../tests/local.neon";
$content = file_get_contents($filename);
if ($content === false) {
    throw new RuntimeException("File $filename does not exist or cannot be read.");
}
$config = Neon::decode($content);

$connection = new Nextras\Dbal\Connection($config["dbal"]);
$sqlsFolder = __DIR__ . "/../app/sqls";
$files = ["structure", "data_basic", "data_test"];
$parser = $connection->getPlatform()->createMultiQueryParser();
foreach ($files as $file) {
    echo "Executing file: $file.sql ... ";
    Tracy\Debugger::timer($file);
    foreach ($parser->parseFile("$sqlsFolder/$file.sql") as $sql) {
        $connection->query("%raw", $sql);
    }
    $time = round(Tracy\Debugger::timer($file), 2);
    echo "Done in $time second(s)\n";
}

$time = round(Tracy\Debugger::timer("setup_db"), 2);
echo "\nTotal time: $time second(s)\n";
