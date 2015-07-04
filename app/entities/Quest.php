<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for quest
 * 
 * @author Jakub Konečný
 */
class Quest extends \Nette\Object {
  /** @var int */
  public $id;
  /** @var string */
  public $name;
  /** @var string */
  public $introduction;
  /** @var string */
  public $end_text;
  /** @var int */
  public $cost_money = 0;
  /** @var int */
  public $needed_level = 0;
  /** @var int */
  public $needed_quest = NULL;
  /** @var int */
  public $needed_item = NULL;
  /** @var int */
  public $item_amount;
  /** @var bool */
  public $item_lose;
  /** @var int */
  public $reward_money;
  /** @var int */
  public $reward_xp;
  /** @var int */
  public $reward_item;
  /** @var int */
  public $npc_start;
  /** @var int */
  public $npc_end;
  /** @var int */
  public $order;
  /** @var bool */
  public $progress = false;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->name != "quests") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
}
?>