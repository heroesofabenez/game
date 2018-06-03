<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Security as NS,
    HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\Character;

  /**
   * Authenticator for the game
   * 
   * @author Jakub Konečný
   */
final class UserManager implements NS\IAuthenticator {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var Permissions */
  protected $permissionsModel;
  /** @var Profile */
  protected $profileModel;
  /** @var IUserToCharacterMapper */
  protected $userToCharacterMapper;
  
  public function __construct(ORM $orm, Permissions $permissionsModel, Profile $profileModel, IUserToCharacterMapper $userToCharacterMapper) {
    $this->orm = $orm;
    $this->permissionsModel = $permissionsModel;
    $this->profileModel = $profileModel;
    $this->userToCharacterMapper = $userToCharacterMapper;
  }
  
  /**
   * Return real user's id
   */
  protected function getRealId(): int {
    return $this->userToCharacterMapper->getRealId();
  }
  
  /**
   * Logins the user
   */
  public function authenticate(array $credentials): NS\Identity {
    $uid = $this->getRealId();
    if($uid == 0) {
      return new NS\Identity(0, "guest");
    }
    $char = $this->orm->characters->getByOwner($uid);
    if(is_null($char)) {
      return new NS\Identity(-1, "guest");
    }
    $data = [
      "name" => $char->name, "race" => $char->race->id, "gender" => $char->gender,
      "occupation" => $char->occupation->id,
      "specialization" => (!is_null($char->specialization)) ? $char->specialization->id : NULL,
      "level" => $char->level,  "stage" => $char->currentStage->id,
      "white_karma" => $char->whiteKarma, "neutral_karma" => $char->neutralKarma, "dark_karma" => $char->darkKarma
    ];
    $data["guild"] = 0;
    $role = "player";
    if(!is_null($char->guild)) {
      $data["guild"] = $char->guild->id;
      $role = $char->guildrank->name;
    }
    return new NS\Identity($char->id, $role, $data);
  }
  
  /**
   * Creates new character
   *
   * @return array|NULL Stats of new character
   */
  public function create(array $values): ?array {
    $data = [
      "name" => $values["name"], "race" => $values["race"],
      "occupation" => $values["class"], "owner" => $this->getRealId(),
    ];
    if($values["gender"] == 1) {
      $data["gender"] = "male";
    } else {
      $data["gender"] = "female";
    }
    
    $character = $this->orm->characters->getByName($data["name"]);
    if(!is_null($character)) {
      return NULL;
    }
    
    $character = new Character();
    $this->orm->characters->attach($character);
    foreach ($data as $key => $value) {
      $character->$key = $value;
    }
    $data["strength"] = $character->strength = $character->occupation->strength + $character->race->strength;
    $data["dexterity"] = $character->dexterity = $character->occupation->dexterity + $character->race->dexterity;
    $data["constitution"] = $character->constitution = $character->occupation->constitution + $character->race->constitution;
    $data["intelligence"] = $character->intelligence = $character->occupation->intelligence + $character->race->intelligence;
    $data["charisma"] = $character->charisma = $character->occupation->charisma + $character->race->charisma;
    $this->orm->characters->persistAndFlush($character);
  
    $data["class"] = $values["class"];
    $data["race"] = $values["race"];
  
    unset($data["occupation"]);
    return $data;
  }
}
?>