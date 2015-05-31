<?php
namespace HeroesofAbenez;

use Nette\Utils\Arrays;

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
  public $middle_text;
  /** @var string */
  public $end_text;
  /** @var int */
  public $cost_money;
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
  public $npc_start;
  /** @var int */
  public $npc_end;
  /** @var int */
  public $order;
  
  function __construct($id, $name, $introduction, $middle_text, $end_text,
    $reward_money, $reward_xp, $npc_start, $npc_end, $order,
    $needed_item = NULL, $item_amount = 0, $item_lose = false) {
    $this->id = $id;
    $this->name = $name;
    $this->introduction = $introduction;
    $this->middle_text = $middle_text;
    $this->end_text = $end_text;
    $this->reward_money = $reward_money;
    $this->reward_xp = $reward_xp;
    $this->npc_start = $npc_start;
    $this->npc_end = $npc_end;
    $this->order = $order;
    if(is_int($needed_item)) $this->needed_item = $needed_item;
    $this->item_amount = $item_amount;
    $this->item_lose = (bool) $item_lose;
  }
}

/**
 * Quest Model
 * 
 * @author Jakub Konečný
 */
class QuestModel extends \Nette\Object {
  /**
   * Gets list of quests
   * 
   * @param \Nette\DI\Container $container
   * @param int $npc Return quests only from certain npc, 0 = all npcs
   * @return array
   */
  static function listOfQuests(\Nette\DI\Container $container, $npc = 0) {
    $return = array();
    $cache = $container->getService("caches.quests");
    $quests = $cache->load("quests");
    if($quests === NULL) {
      $db = $container->getService("database.default.context");
      $quests = $db->table("quests");
      foreach($quests as $quest) {
        $return[$quest->id] =
          new Quest($quest->id, $quest->name, $quest->introduction, $quest->middle_text,
            $quest->end_text, $quest->reward_money, $quest->reward_xp, $quest->npc_start,
            $quest->npc_end, $quest->order);
      }
      $cache->save("quests", $return);
    } else {
      $return = $quests;
    }
    if($npc > 0) {
      foreach($return as $quest) {
        if($quest->npc_start != $npc OR $quest->npc_end != $npc) unset($return[$quest->id]);
      }
    }
    return $return;
  }
  
  /**
   * Gets info about specified quest
   * 
   * @param int $id Quest's id
   * @param \Nette\DI\Container $container
   * @return \HeroesofAbenez\NPC
   */
  static function view($id, \Nette\DI\Container $container) {
    $quests = QuestModel::listOfQuests($container);
    $quest = Arrays::get($quests, $id, false);
    return $quest;
  }
}
?>