<?php
namespace HeroesofAbenez\Model;

use Nette\Security as NS;

  /**
   * Authenticator for the game
   * 
   * @author Jakub Konečný
   */
class UserManager extends \Nette\Object implements NS\IAuthenticator {
  /** @var \Nette\Database\Context Database context */
  protected $db;
  /** @var \HeroesofAbenez\Model\Permissions */
  protected $permissionsModel;
  
  /**
   * @param \Nette\Database\Context $database Database context
   */
  function __construct(\Nette\Database\Context $database, \HeroesofAbenez\Model\Permissions $permissionsModel) {
    $this->db = $database;
    $this->permissionsModel = $permissionsModel;
  }
  
  /**
   * Return real user's id
   * @return int
   */
  protected function getRealId() {
    $dev_servers = array("localhost", "kobliha", "hoa.local");
    if(in_array($_SERVER["SERVER_NAME"], $dev_servers)) {
      $uid = 1;
    } else {
      $ch = curl_init("http://heroesofabenez.tk/auth.php");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $uid = curl_exec($ch);
      curl_close($ch);
    }
    return $uid;
  }
  
  /**
   * Logins the user
   * @param array $credentials not really used
   * @return \Nette\Security\Identity User's identity
   */
  function authenticate(array $credentials) {
    $uid = $this->getRealId();
    if($uid == 0) return new NS\Identity(0, "guest");
    $chars = $this->db->table("characters")->where("owner", $uid);
    if($chars->count() == 0) return new NS\Identity(-1, "guest");
    foreach($chars as $char) {
      if($char->owner == $uid) break;
    }
    $data = array(
      "name" => $char->name, "race" => $char->race, "gender" => $char->gender,
      "occupation" => $char->occupation, "specialization" => $char->specialization,
      "level" => $char->level, "guild" => $char->guild, "stage" => $char->current_stage,
      "white_karma" => $char->white_karma, "neutral_karma" => $char->neutral_karma, "dark_karma" => $char->dark_karma
    );
    if($char->guild > 0) {
      $role = $this->permissionsModel->getRoleName($char->guildrank);
    }
    else $role = "player";
    return new NS\Identity($char->id, $role, $data);
  }
  
  /**
   * Creates new character
   * 
   * @param \Nette\Utils\ArrayHash $values
   * @return array Stats of new character
   */
  function create($values) {
    $data = array(
      "name" => $values["name"], "race" => $values["race"],
      "occupation" => $values["class"], "gender" => $values["gender"]
    );
    $chars = $this->db->table("characters")->where("name", $data["name"]);
    if($chars->count() > 0) return false;
    
    $race = $this->getRace($values["race"]);
    $class = $this->getClass($values["class"]);
    $data["strength"] = $class->strength + $race->strength;
    $data["dexterity"] = $class->dexterity + $race->dexterity;
    $data["constitution"] = $class->constitution + $race->constitution;
    $data["intelligence"] = $class->intelligence + $race->intelligence;
    $data["charisma"] = $class->charisma + $race->charisma;
    $data["owner"] = $this->getRealId();
    $this->db->query("INSERT INTO characters", $data);
    
    $data["class"] = $values["class"];
    $data["race"] = $values["race"];
    if($data["gender"]  == 1) $data["gender"] = "male";
    else $data["gender"] = "female";
    unset($data["occupation"]);
    return $data;
  }
}
?>