<?php
use Nette\Security as NS;

  /**
   * Authenticator for the game
   * 
   * @author Jakub Konečný
   * 
   * @property Nette\Database\Context $db Database context
   */
class Authenticator extends Nette\Object implements NS\IAuthenticator {
  public $db;
  
  /**
   * @param Nette\Database\Context $database Database context
   */
  function __construct(Nette\Database\Context $database) {
    $this->db = $database;
  }
  
  /**
   * Logins the user
   * @param array $credentials not really used
   * @return Nette\Security\Identity User's identity
   */
  function authenticate(array $credentials) {
    $dev_servers = array("localhost", "kobliha", "test.heroesofabenez.tk");
    if(in_array($_SERVER["SERVER_NAME"], $dev_servers)) {
      $uid = 1;
    } else {
      define('WP_USE_THEMES', false);
      require( WWW_DIR . '/../wp-blog-header.php' );
      $uid = get_current_user_id();
    }
    if($uid == 0) return new NS\Identity(0, "guest");
    $chars = $this->db->table("characters")->where("owner", $uid);
    if($chars->count("*") == 0) return new NS\Identity(-1, "guest");
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