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
      $uid = 0;
    } else {
      define('WP_USE_THEMES', false);
      require( WWW_DIR . '/../wp-blog-header.php' );
      $uid = get_current_user_id();
    }
    $chars = $this->db->table("characters");
    foreach($chars as $char) {
      if($char->owner == $uid) break;
    }
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
    if($char->guild > 0) $role = $char->rank->name;
    else $role = "player";
    return new NS\Identity($char->id, $role, $data);
  }
}
?>