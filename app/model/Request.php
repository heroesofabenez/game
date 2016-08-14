<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Request as RequestEntity,
    Nette\NotImplementedException;

/**
 * Request Model
 * 
 * @author Jakub Konečný
 */
class Request {
  use \Nette\SmartObject;
  
   /** @var \Nette\Security\User */
  protected $user;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \HeroesofAbenez\Model\Profile */
  protected $profileModel;
  /** @var \HeroesofAbenez\Model\Guild */
  protected $guildModel;
  
  /**
   * @param \Nette\Security\User $user
   * @param \Nette\Database\Context $db
   * @param \HeroesofAbenez\Model\Profile $profileModel
   * @param \HeroesofAbenez\Model\Guild $guildModel
   */
  function __construct(\Nette\Security\User $user,\Nette\Database\Context $db, Profile $profileModel, Guild $guildModel) {
    $this->user = $user;
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
  function canShow($requestId) {
    $request = $this->db->table("requests")->get($requestId);
    switch($request->type) {
  case "friendship":
  case "group_join":
    if($request->from == $this->user->id OR $request->to == $this->user->id) return true;
    else return false;
    break;
  case "guild_join":
    if($request->to == $this->user->id) return true;
    $leader = $this->db->table("characters")->get($request->from);
    $guild = $leader->guild;
    if($this->user->identity->guild == $guild AND $this->user->isAllowed("guild", "invite")) {
      return true;
    } else {
      return false;
    }
    break;
  case "guild_app":
    if($request->from == $this->user->id) return true;
    $leader = $this->db->table("characters")->get($request->to);
    $guild = $leader->guild;
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
  function canChange($requestId) {
    $request = $this->db->table("requests")->get($requestId);
    if($request->from == $this->user->id) return false;
    if($request->to == $this->user->id) return true;
    if($request->type == "guild_app") {
      $leader = $this->db->table("characters")->get($request->to);
      $guild = $leader->guild;
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
   * @return \HeroesofAbenez\Entities\Request
   * @throws RequestNotFoundException
   * @throws CannotSeeRequestException
   */
  function show($id) {
    $requestRow = $this->db->table("requests")->get($id);
    if(!$requestRow) throw new RequestNotFoundException;
    if(!$this->canShow($id)) throw new CannotSeeRequestException;
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
   * @throws \Nette\NotImplementedException
   */
  function accept($id) {
    $request = $this->show($id);
    if(!$request) throw new RequestNotFoundException;
    if(!$this->canShow($id)) throw new CannotSeeRequestException;
    if(!$this->canChange($id)) throw new CannotAcceptRequestException;
    if($request->status !== "new") throw new RequestAlreadyHandledException;
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
  function decline($id) {
    $request = $this->show($id);
    if(!$request) throw new RequestNotFoundException;
    if(!$this->canShow($id)) throw new CannotSeeRequestException;
    if(!$this->canChange($id)) throw new CannotDeclineRequestException;
    if($request->status !== "new") throw new RequestAlreadyHandledException;
    $data = ["status" => "declined"];
    $this->db->query("UPDATE requests SET ? WHERE id=?", $data, $id);
  }
}

class CannotSeeRequestException extends AccessDenied {
  
}

class CannotAcceptRequestException extends AccessDenied {
  
}

class CannotDeclineRequestException extends AccessDenied {
  
}

class RequestAlreadyHandledException extends InvalidStateException {
  
}

class RequestNotFoundException extends RecordNotFoundException {
  
}
?>