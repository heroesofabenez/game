<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;
class Pet extends Nette\Object {
  private $id;
  private $type;
  private $name;
  private $bonus_stat;
  private $bonus_value;
  function __construct($id, $type, $name = "", $bonus_stat, $bonus_value) {
    $this->id = $id;
    $this->type = $type;
    $this->name = $name;
    $this->bonus_stat = $bonus_stat;
    $this->bonus_value = $bonus_value;
  }
  
  function deployParams() {
    return array(
      "id" => "pet" . $this->id . "bonusEffect",
      "type" => "buff",
      "stat" => $this->bonus_stat,
      "value" => $this->bonus_value,
      "source" => "pet",
      "duration" => "combat"
    );
  }
}
?>