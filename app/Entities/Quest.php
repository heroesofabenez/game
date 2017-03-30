<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Data structure for quest
 * 
 * @author Jakub Konečný
 */
class Quest extends BaseEntity {
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $introduction;
  /** @var string */
  protected $end_text;
  /** @var int */
  protected $cost_money = 0;
  /** @var int */
  protected $needed_level = 0;
  /** @var int|NULL */
  protected $needed_quest = NULL;
  /** @var int|NULL */
  protected $needed_item = NULL;
  /** @var int */
  protected $item_amount;
  /** @var bool */
  protected $item_lose;
  /** @var int */
  protected $reward_money;
  /** @var int */
  protected $reward_xp;
  /** @var int */
  protected $reward_item;
  /** @var int */
  protected $npc_start;
  /** @var int */
  protected $npc_end;
  /** @var int */
  protected $order;
  /** @var bool */
  protected $progress = false;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->getName() != "quests") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
  
  /**
   * @param bool $value
   */
  function setProgress(bool $value) {
    $this->progress = $value;
  }
}
?>