<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Request as RequestEntity,
    Nette\Application\BadRequestException,
    Nette\Application\ForbiddenRequestException,
    Nette\NotImplementedException;

/**
 * Request Model
 * 
 * @author Jakub Konečný
 */
class Request extends \Nette\Object {
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
   * @throws \Nette\Application\BadRequestException
   * @throws \Nette\Application\ForbiddenRequestException
   */
  function show($id) {
    $requestRow = $this->db->table("requests")->get($id);
    if(!$requestRow) throw new BadRequestException;
    if(!$this->canShow($id)) throw new ForbiddenRequestException;
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
   * @throws \Nette\Application\BadRequestException
   * @throws \Nette\Application\ForbiddenRequestException
   * @throws \Nette\NotImplementedException
   */
  function accept($id) {
    $request = $this->show($id);
    if(!$request) throw new BadRequestException;
    if(!$this->canShow($id)) throw new ForbiddenRequestException("You can't see this request.");
    if(!$this->canChange($id)) throw new ForbiddenRequestException("You can't accept this request.");
    if($request->status !== "new") throw new ForbiddenRequestException("This request was already handled.");
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
    $data2 = array("status" => "accepted");
    $this->db->query("UPDATE requests SET ? WHERE id=?", $data2, $id);
  }
  
  /**
   * Decline specified request
   * 
   * @param int $id Request's id
   * @return void
   * @throws \Nette\Application\BadRequestException
   * @throws \Nette\Application\ForbiddenRequestException
   */
  function decline($id) {
    $request = $this->show($id);
    if(!$request) throw new BadRequestException;
    if(!$this->canShow($id)) throw new ForbiddenRequestException("You can't see this request.");
    if(!$this->canChange($id)) throw new ForbiddenRequestException("You can't decline this request.");
    if($request->status !== "new") throw new ForbiddenRequestException("This request was already handled.");
    $data = array("status" => "declined");
    $this->db->query("UPDATE requests SET ? WHERE id=?", $data, $id);
  }
}
?>