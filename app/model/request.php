<?php
namespace HeroesofAbenez;

/**
 * Data structure for request
 *
 * @author Jakub Konečný
 */
class Request extends \Nette\Object {
  /** @var int */
  public $id;
  /** @var string */
  public $from;
  /** @var string */
  public $to;
  /** @var string */
  public $type;
  public $sent;
  /** @var string */
  public $status;
  
  function __construct($id, $from, $to, $type, $sent, $status) {
    $this->id = $id;
    $this->from = $from;
    $this->to = $to;
    $this->type = $type;
    $this->sent = $sent;
    $this->status = $status;
  }
}

class RequestModel extends \Nette\Object {
   /** @var \Nette\Security\User */
  protected $user;
  /** @var \Nette\Database\Context */
  protected $db;
  
  /**
   * @param \Nette\Security\User $user
   * @param \Nette\Database\Context $db
   */
  function __construct(\Nette\Security\User $user,\Nette\Database\Context $db) {
    $this->user = $user;
    $this->db = $db;
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
   * @param type $id Request's id
   * @param \Nette\Di\Container $container
   * @return \HeroesofAbenez\Request
   */
  function show($id, \Nette\Di\Container $container) {
    $requestRow = $this->db->table("requests")->get($id);
    if(!$requestRow) return NULL;
    $canShow = $this->canShow($id);
    if(!$canShow) return false;
    $from = Profile::getCharacterName($requestRow->from, $container);
    $to = Profile::getCharacterName($requestRow->to, $container);
    $return = new Request($requestRow->id, $from, $to, $requestRow->type, $requestRow->sent, $requestRow->status);
    return $return;
  }
  
  /**
   * Accept specified request
   * 
   * @param int $id Request's id
   * @param \Nette\Di\Container $container
   * @return int Error code/1 on success
   */
  function accept($id, \Nette\Di\Container $container) {
    $request = $this->show($id, $container);
    if(!$request) return 2;
    $canShow = $this->canShow($id);
    if(!$canShow) return 3;
    $canChange = $this->canChange($id);
    if(!$canChange) return 4;
    if($request->status !== "new") return 5;
    switch($request->type) {
  case "friendship":
    return 6;
    break;
  case "group_join":
    return 6;
    break;
  case "guild_app":
    $uid = Profile::getCharacterId($request->from, $container);
    $uid2 = Profile::getCharacterId($request->to, $container);
    $gid = Profile::getCharacterGuild($uid2, $this->db);
    GuildModel::join($uid, $gid, $container);
    break;
  case "guild_join":
    $uid = Profile::getCharacterId($request->to, $container);
    $uid2 = Profile::getCharacterId($request->from, $container);
    $gid = Profile::getCharacterGuild($uid2, $this->db);
    GuildModel::join($uid, $gid, $container);
    break;
    }
    $data2 = array("status" => "accepted");
    $this->db->query("UPDATE requests SET ? WHERE id=?", $data2, $id);
    return 1;
  }
  
  /**
   * Decline specified request
   * 
   * @param int $id Request's id
   * @param \Nette\Di\Container $container
   * @return int Error code/1 on success
   */
  function decline($id, \Nette\Di\Container $container) {
    $request = $this->show($id, $container);
    if(!$request) return 2;
    $canShow = $this->canShow($id);
    if(!$canShow) return 3;
    $canChange = $this->canChange($id);
    if(!$canChange) return 4;
    if($request->status !== "new") return 5;
    $data = array("status" => "declined");
    $this->db->query("UPDATE requests SET ? WHERE id=?", $data, $id);
    return 1;
  }
}