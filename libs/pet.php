<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;
class Pet extends Object {
  private $id;
  private $type;
  private $name;
  private $bonus_stat;
  private $bonus_value;
  private $bonus_duration;
  function __construct($id, $type, $name = "", $bonus_stat, $bonus_value, $bonus_duration) {
    $this->id = $id;
    $this->type = $type;
    $this->name = $name;
    $this->bonus_stat = $bonus_stat;
    $this->bonus_value = $bonus_value;
    $this->bonus_duration = $bonus_duration;
  }
  
  function deployParams() {
    return array(
      "stat" => $this->bonus_stat,
      "value" => $this->bonus_value,
      "duration" => $this->bonus_duration
    );
  }
}
?>