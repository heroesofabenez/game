<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Security\Identity;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\Character;
use Nette\Security\IIdentity;

/**
 * Authenticator for the game
 *
 * @author Jakub Konečný
 */
final class UserManager implements \Nette\Security\IAuthenticator {
  use \Nette\SmartObject;

  protected ORM $orm;
  protected Permissions $permissionsModel;
  protected Profile $profileModel;
  protected IUserToCharacterMapper $userToCharacterMapper;
  protected CharacterBuilder $cb;
  
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
  public function authenticate(array $credentials): IIdentity {
    $uid = $this->getRealId();
    if($uid === IUserToCharacterMapper::USER_ID_NOT_LOGGED_IN) {
      return new Identity(IUserToCharacterMapper::USER_ID_NOT_LOGGED_IN, "guest");
    }
    $char = $this->orm->characters->getByOwner($uid);
    if($char === null) {
      return new Identity(IUserToCharacterMapper::USER_ID_NO_CHARACTER, "guest");
    }
    $data = [
      "name" => $char->name, "race" => $char->race->id, "gender" => $char->gender,
      "class" => $char->class->id,
      "specialization" => ($char->specialization !== null) ? $char->specialization->id : null,
      "level" => $char->level, "stage" => $char->currentStage->id,
      "white_karma" => $char->whiteKarma, "dark_karma" => $char->darkKarma,
    ];
    $data["guild"] = 0;
    $role = "player";
    if($char->guild !== null) {
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
    if($character !== null) {
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
    foreach($data as $key => $value) {
      $character->$key = $value;
    }
    $this->orm->characters->persistAndFlush($character);
  
    $data["class"] = $values["class"];
    $data["race"] = $values["race"];

    return $data;
  }
}
?>