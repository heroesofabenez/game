<?php
declare(strict_types=1);

use Nette\Neon\Neon;
use Nextras\Dbal\QueryException;

require __DIR__ . "/../vendor/autoload.php";

$config = Neon::decode(file_get_contents(__DIR__ . "/../tests/local.neon"));

$connection = new Nextras\Dbal\Connection($config["dbal"]);

try {
  $connection->query("SET foreign_key_checks = 0");
  /** @var \Nextras\Dbal\Result\Result $tables */
  $tables = $connection->query("SHOW TABLES");
  while($table = $tables->fetchField(0)) {
    $connection->query("DROP TABLE $table");
  }
} catch(QueryException $e) { // @codingStandardsIgnoreLine

}
?>