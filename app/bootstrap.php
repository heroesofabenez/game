<?php
declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

$configurator = new Nette\Bootstrap\Configurator();
if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
    $localConfig = \Nette\Neon\Neon::decodeFile(__DIR__ . "/config/local.neon");
    if (isset($localConfig["http"]["proxy"])) {
        $proxyServer = match (get_debug_type($localConfig["http"]["proxy"])) {
            "array" => (array) $localConfig["http"]["proxy"],
            default => [$localConfig["http"]["proxy"]]
        };
        if (in_array($_SERVER["REMOTE_ADDR"], $proxyServer, true)) {
            $configurator->setDebugMode($_SERVER["REMOTE_ADDR"]);
        }
    }
}
$configurator->enableTracy(__DIR__ . "/../log");
$configurator->setTempDirectory(__DIR__ . "/../temp");
$configurator->addConfig(__DIR__ . "/config/main.neon");
$configurator->addConfig(__DIR__ . "/config/local.neon");
$container = $configurator->createContainer();

return $container;
