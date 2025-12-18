<?php
declare(strict_types=1);

use Nette\Neon\Neon;

require __DIR__ . "/../vendor/autoload.php";

$filename = __DIR__ . "/../tests/local.neon";
$content = file_get_contents($filename);
if ($content === false) {
    throw new RuntimeException("File $filename does not exist or cannot be read.");
}
$config = Neon::decode($content);

$config["dbal"]["host"] = "127.0.0.1";
$config["dbal"]["password"] = "";
$config["dbal"]["port"] = 3306;
file_put_contents($filename, Neon::encode($config, true));
