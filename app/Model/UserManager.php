<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Security as NS;

  /**
   * Authenticator for the game
   * 
   * @author Jakub Konečný
   */
class UserManager implements NS\IAuthenticator {
  use \Nette\SmartObject;
  
  /** @var \Nette\Database\Context Database context */
  protected $db;
  /** @var Permissions */
  protected $permissionsModel;
  /** @var Profile */
  protected $profileModel;
  /** @var array */
  protected $devServers;
  
  /**
   * @param array $devServers
   * @param \Nette\Database\Context $db
   * @param \HeroesofAbenez\Model\Permissions $permissionsModel
   * @param \HeroesofAbenez\Model\Profile $profileModel
   */
  function __construct(array $devServers, \Nette\Database\Context $db, Permissions $permissionsModel, Profile $profileModel) {
    $this->db = $db;
    $this->permissionsModel = $permissionsModel;
    $this->profileModel = $profileModel;
    $this->devServers = $devServers;
  }
  
  /**
   * Return real user's id
   * 
   * @return int
   */
  protected function getRealId(): int {
    if(in_array($_SERVER["SERVER_NAME"], $this->devServers)) {
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
   * 
   * @param array $credentials not really used
   * @return NS\Identity User's identity
   */
  function authenticate(array $credentials): NS\Identity {
    $uid = $this->getRealId();
    if($uid == 0) return new NS\Identity(0, "guest");
    $chars = $this->db->table("characters")->where("owner", $uid);
    if($chars->count() == 0) return new NS\Identity(-1, "guest");
    $char = $chars->fetch();
    $data = [
      "name" => $char->name, "race" => $char->race, "gender" => $char->gender,
      "occupation" => $char->occupation, "specialization" => $char->specialization,
      "level" => $char->level, "guild" => $char->guild, "stage" => $char->current_stage,
      "white_karma" => $char->white_karma, "neutral_karma" => $char->neutral_karma, "dark_karma" => $char->dark_karma
    ];
    if($char->guild > 0) {
      $role = $this->permissionsModel->getRoleName($char->guildrank);
    }
    else $role = "player";
    return new NS\Identity($char->id, $role, $data);
  }
  
  /**
   * Creates new character
   * 
   * @param array $values
   * @return array|bool Stats of new character
   */
  function create(array $values) {
    $data = [
      "name" => $values["name"], "race" => $values["race"],
      "occupation" => $values["class"], "gender" => $values["gender"]
    ];
    $chars = $this->db->table("characters")->where("name", $data["name"]);
    if($chars->count() > 0) return false;
    
    $race = $this->profileModel->getRace($values["race"]);
    $class = $this->profileModel->getClass($values["class"]);
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