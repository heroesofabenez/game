<?php
declare(strict_types=1);

use Nette\Neon\Neon;
use Nextras\Dbal\Drivers\Exception\QueryException;

require __DIR__ . "/../vendor/autoload.php";

$filename = __DIR__ . "/../tests/local.neon";
$content = file_get_contents($filename);
if ($content === false) {
    throw new RuntimeException("File $filename does not exist or cannot be read.");
}
$config = Neon::decode($content);

$connection = new Nextras\Dbal\Connection($config["dbal"]);

try {
    $connection->query("SET foreign_key_checks = 0");
    /** @var \Nextras\Dbal\Result\Result $tables */
    $tables = $connection->query("SHOW TABLES");
    while ($table = $tables->fetchField(0)) {
        $connection->query("DROP TABLE $table"); // @phpstan-ignore argument.type
    }
} catch (QueryException $e) {
}
