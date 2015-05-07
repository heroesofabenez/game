<?php
use Nette\Security as NS;

class Authenticator extends Nette\Object implements NS\IAuthenticator {
  public $db;
  function __construct(Nette\Database\Context $database) {
    $this->db = $database;
  }
  
  function authenticate(array $credentials) {
    $dev_servers = array("localhost", "kobliha", "test.heroesofabenez.tk");
    if(in_array($_SERVER["SERVER_NAME"], $dev_servers)) {
      $id = 1;
    } else { }
    $char = $this->db->table("characters")->get($id);
    $race = $this->db->table("character_races")->get($char->race);
    $occupation = $this->db->table("character_classess")->get($char->occupation);
    if(is_int($char->specialization)) {
      $specialization = $this->db->table("character_specializations")->get($char->specialization)->name;
    } else {
      $specialization = null;
    }
    $specialization = $this->db->table("character_specializations")->get($char->specialization);
    $data = array(
      "name" => $char->name, "race" => $race->name, "gender" => $char->gender,
      "occupation" => $occupation->name, "specialization" => $specialization,
      "level" => $char->level, "guild" => $char->guild,
    );
    return new NS\Identity($id, $char->rank->name, $data);
  }
}
?>