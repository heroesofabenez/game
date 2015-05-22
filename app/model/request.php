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
  static function show($id, \Nette\Database\Context $db) {
    $requestRow = $db->table("requests")->get($id);
    if(!$requestRow) return false;
    $from = $db->table("characters")->get($requestRow->from);
    $to = $db->table("characters")->get($requestRow->to);
    $return = new Request($requestRow->id, $from->name, $to->name, $requestRow->type, $requestRow->sent, $requestRow->status);
    return $return;
  }
}