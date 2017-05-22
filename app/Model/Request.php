<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Request as RequestEntity,
    Nette\NotImplementedException,
    HeroesofAbenez\Orm\Model as ORM;

/**
 * Request Model
 * 
 * @author Jakub Konečný
 */
class Request {
  use \Nette\SmartObject;
  
   /** @var \Nette\Security\User */
  protected $user;
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var Profile */
  protected $profileModel;
  /** @var Guild */
  protected $guildModel;
  
  function __construct(\Nette\Security\User $user, ORM $orm, \Nette\Database\Context $db, Profile $profileModel, Guild $guildModel) {
    $this->user = $user;
    $this->orm = $orm;
    $this->db = $db;
    $this->profileModel = $profileModel;
    $this->guildModel = $guildModel;
  }
  
  /**
   * Can player see the request?
   * 
   * @param int $requestId
   * @return bool
   */
  function canShow(int $requestId): bool {
    $request = $this->db->table("requests")->get($requestId);
    switch($request->type) {
      case "friendship":
      case "group_join":
        if($request->from == $this->user->id OR $request->to == $this->user->id) {
          return true;
        } else {
          return false;
        }
        break;
      case "guild_join":
        if($request->to == $this->user->id) {
          return true;
        }
        $leader = $this->orm->characters->getById($request->from);
        $guild = $leader->guild->id;
        if($this->user->identity->guild == $guild AND $this->user->isAllowed("guild", "invite")) {
          return true;
        } else {
          return false;
        }
        break;
      case "guild_app":
        if($request->from == $this->user->id) {
          return true;
        }
        $leader = $this->orm->characters->getById($request->to);
        $guild = $leader->guild->id;
        if($this->user->identity->guild == $guild AND $this->user->isAllowed("guild", "invite")) {
          return true;
        } else {
          return false;
        }
        break;
    }
  }
  
  /**
   * Can player accept/decline the request?
   * 
   * @param int $requestId
   * @return bool
   */
  function canChange(int $requestId): bool {
    $request = $this->db->table("requests")->get($requestId);
    if($request->from == $this->user->id) {
      return false;
    }
    if($request->to == $this->user->id) {
      return true;
    }
    if($request->type == "guild_app") {
      $leader = $this->orm->characters->getById($request->to);
      $guild = $leader->guild->id;
      if($this->user->identity->guild == $guild AND $this->user->isAllowed("guild", "invite")) {
        return true;
      } else {
        return false;
      }
    }
    return false;
  }
  
  /**
   * Gets data about specified request
   * 
   * @param int $id Request's id
   * @return RequestEntity
   * @throws RequestNotFoundException
   * @throws CannotSeeRequestException
   */
  function show(int $id): RequestEntity {
    $requestRow = $this->db->table("requests")->get($id);
    if(!$requestRow) {
      throw new RequestNotFoundException;
    }
    if(!$this->canShow($id)) {
      throw new CannotSeeRequestException;
    }
    $from = $this->profileModel->getCharacterName($requestRow->from);
    $to = $this->profileModel->getCharacterName($requestRow->to);
    $return = new RequestEntity($requestRow->id, $from, $to, $requestRow->type, $requestRow->sent, $requestRow->status);
    return $return;
  }
  
  /**
   * Accept specified request
   * 
   * @param int $id Request's id
   * @return void
   * @throws RequestNotFoundException
   * @throws CannotSeeRequestException
   * @throws CannotAcceptRequestException
   * @throws RequestAlreadyHandledException
   * @throws NotImplementedException
   */
  function accept(int $id): void {
    $request = $this->show($id);
    if(!$request) {
      throw new RequestNotFoundException;
    }
    if(!$this->canShow($id)) {
      throw new CannotSeeRequestException;
    }
    if(!$this->canChange($id)) {
      throw new CannotAcceptRequestException;
    }
    if($request->status !== "new") {
      throw new RequestAlreadyHandledException;
    }
    switch($request->type) {
      case "friendship":
    throw new NotImplementedException;
      break;
      case "group_join":
    throw new NotImplementedException;
      break;
      case "guild_app":
        $uid = $this->profileModel->getCharacterId($request->from);
        $uid2 = $this->profileModel->getCharacterId($request->to);
        $gid = $this->profileModel->getCharacterGuild($uid2);
        $this->guildModel->join($uid, $gid);
    break;
      case "guild_join":
        $uid = $this->profileModel->getCharacterId($request->to);
        $uid2 = $this->profileModel->getCharacterId($request->from);
        $gid = $this->profileModel->getCharacterGuild($uid2);
        $this->guildModel->join($uid, $gid);
    break;
    }
    $data2 = ["status" => "accepted"];
    $this->db->query("UPDATE requests SET ? WHERE id=?", $data2, $id);
  }
  
  /**
   * Decline specified request
   * 
   * @param int $id Request's id
   * @return void
   * @throws RequestNotFoundException
   * @throws CannotSeeRequestException
   * @throws CannotDeclineRequestException
   * @throws RequestAlreadyHandledException
   */
  function decline(int $id): void {
    $request = $this->show($id);
    if(!$request) {
      throw new RequestNotFoundException;
    }
    if(!$this->canShow($id)) {
      throw new CannotSeeRequestException;
    }
    if(!$this->canChange($id)) {
      throw new CannotDeclineRequestException;
    }
    if($request->status !== "new") {
      throw new RequestAlreadyHandledException;
    }
    $data = ["status" => "declined"];
    $this->db->query("UPDATE requests SET ? WHERE id=?", $data, $id);
  }
}
?>