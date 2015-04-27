<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;
define("CONFIG_FILE", APP_DIR . "/config.ini");
class Config_Ini extends Nette\Object {
  private $config;
  function __construct($file = CONFIG_FILE) {
    $this->config = parse_ini_file($file, true); 
  }

  function getConfig() {
    return $this->config;  
  }
}
?>