<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Security\Identity;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\Character;

  /**
   * Authenticator for the game
   * 
   * @author Jakub Konečný
   */
final class UserManager implements \Nette\Security\IAuthenticator {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var Permissions */
  protected $permissionsModel;
  /** @var Profile */
  protected $profileModel;
  /** @var IUserToCharacterMapper */
  protected $userToCharacterMapper;
  /** @var CharacterBuilder */
  protected $cb;
  
  public function __construct(ORM $orm, Permissions $permissionsModel, Profile $profileModel, IUserToCharacterMapper $userToCharacterMapper, CharacterBuilder $cb) {
    $this->orm = $orm;
    $this->permissionsModel = $permissionsModel;
    $this->profileModel = $profileModel;
    $this->userToCharacterMapper = $userToCharacterMapper;
    $this->cb = $cb;
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
  public function authenticate(array $credentials): Identity {
    $uid = $this->getRealId();
    if($uid === 0) {
      return new Identity(0, "guest");
    }
    $char = $this->orm->characters->getByOwner($uid);
    if(is_null($char)) {
      return new Identity(-1, "guest");
    }
    $data = [
      "name" => $char->name, "race" => $char->race->id, "gender" => $char->gender,
      "class" => $char->class->id,
      "specialization" => (!is_null($char->specialization)) ? $char->specialization->id : null,
      "level" => $char->level, "stage" => $char->currentStage->id,
      "white_karma" => $char->whiteKarma, "dark_karma" => $char->darkKarma,
    ];
    $data["guild"] = 0;
    $role = "player";
    if(!is_null($char->guild)) {
      $data["guild"] = $char->guild->id;
      $role = $char->guildrank->name;
    }
    $char->lastActive = new \DateTimeImmutable();
    $this->orm->characters->persistAndFlush($char);
    return new Identity($char->id, $role, $data);
  }
  
  /**
   * Creates new character
   *
   * @return array|null Stats of new character
   */
  public function create(array $values): ?array {
    $character = $this->orm->characters->getByName($values["name"]);
    if(!is_null($character)) {
      return null;
    }

    $class = $this->orm->classes->getById($values["class"]);
    $race = $this->orm->races->getById($values["race"]);
    $data = $this->cb->create($class, $race);
    $data["name"] = $values["name"];
    $data["owner"] = $this->getRealId();
    $data["gender"] = ($values["gender"] === 1) ? "male" : "female";
    $data["class"] = $class;
    $data["race"] = $race;
    
    $character = new Character();
    $this->orm->characters->attach($character);
    foreach ($data as $key => $value) {
      $character->$key = $value;
    }
    $this->orm->characters->persistAndFlush($character);
  
    $data["class"] = $values["class"];
    $data["race"] = $values["race"];

    return $data;
  }
}
?>