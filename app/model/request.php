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
  /**
   * Can player see the request?
   * 
   * @param int $requestId
   * @param \Nette\Security\User $user
   * @param \Nette\Database\Context $db Database context
   * @return bool
   */
  static function canShow($requestId, \Nette\Security\User $user, \Nette\Database\Context $db) {
    $request = $db->table("requests")->get($requestId);
    switch($request->type) {
  case "friendship":
  case "group_join":
    if($request->from == $user->id OR $request->to == $user->id) return true;
    else return false;
    break;
  case "guild_join":
    if($request->to == $user->id) return true;
    $leader = $db->table("characters")->get($request->from);
    $guild = $leader->guild;
    if($user->identity->guild == $guild AND $user->isAllowed("guild", "invite")) {
      return true;
    } else {
      return false;
    }
    break;
  case "guild_app":
    if($request->from == $user->id) return true;
    $leader = $db->table("characters")->get($request->to);
    $guild = $leader->guild;
    if($user->identity->guild == $guild AND $user->isAllowed("guild", "invite")) {
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
   * @param \Nette\Security\User $user
   * @param \Nette\Database\Context $db Database context
   * @return bool
   */
  static function canChange($requestId, \Nette\Security\User $user, \Nette\Database\Context $db) {
    $request = $db->table("requests")->get($requestId);
    if($request->from == $user->id) return false;
    if($request->to == $user->id) return true;
    if($request->type == "guild_app") {
      $leader = $db->table("characters")->get($request->to);
      $guild = $leader->guild;
      if($user->identity->guild == $guild AND $user->isAllowed("guild", "invite")) {
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
   * @param \Nette\Database\Context $db Database context
   * @return \HeroesofAbenez\Request|bool
   */
  static function show($id, \Nette\Database\Context $db) {
    $requestRow = $db->table("requests")->get($id);
    if(!$requestRow) return false;
    $from = $db->table("characters")->get($requestRow->from);
    $to = $db->table("characters")->get($requestRow->to);
    $return = new Request($requestRow->id, $from->name, $to->name, $requestRow->type, $requestRow->sent, $requestRow->status);
    return $return;
  }
  
  static function decline($id, \Nette\Database\Context $db) {
    $data = array("status" => "declined");
    $db->query("UPDATE requests SET ? WHERE id=?", $data, $id);
    return true;
  }
}