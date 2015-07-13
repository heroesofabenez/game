<?php
namespace HeroesofAbenez\Auth;

use Nette\Security as NS;

  /**
   * Authenticator for the game
   * 
   * @author Jakub Konečný
   */
class Authenticator extends \Nette\Object implements NS\IAuthenticator {
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
  static function getRealId() {
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
    $uid = self::getRealId();
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
}
?>