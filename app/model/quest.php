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
  public $npc_start;
  /** @var int */
  public $npc_end;
  /** @var int */
  public $order;
  /** @var bool */
  public $progress = false;
  
  function __construct($id, $name, $introduction, $end_text, $reward_money,
    $reward_xp, $npc_start, $npc_end, $order, $needed_item = NULL, $item_amount = 0,
    $item_lose = false, $cost_money = 0, $needed_level = NULL, $needed_quest = NULL) {
    $this->id = $id;
    $this->name = $name;
    $this->introduction = $introduction;
    $this->end_text = $end_text;
    $this->reward_money = $reward_money;
    $this->reward_xp = $reward_xp;
    $this->npc_start = $npc_start;
    $this->npc_end = $npc_end;
    $this->order = $order;
    if(is_int($needed_item)) $this->needed_item = $needed_item;
    $this->item_amount = $item_amount;
    $this->item_lose = (bool) $item_lose;
    if(is_int($cost_money)) $this->cost_money = $cost_money;
    if(is_int($needed_level)) $this->needed_level = $needed_level;
    if(is_int($needed_quest)) $this->needed_quest = $needed_quest;
  }
}

/**
 * Quest Model
 * 
 * @author Jakub Konečný
 */
class QuestModel extends \Nette\Object {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Nette\Http\Request */
  protected $request;
  
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db, \Nette\Security\User $user) {
    $this->db = $db;
    $this->cache = $cache;
    $this->user = $user;
  }
  
  function setRequest(\Nette\Http\Request $request) {
    $this->request = $request;
  }
  
  /**
   * Gets list of quests
   * 
   * @param int $npc Return quests only from certain npc, 0 = all npcs
   * @return array
   */
  function listOfQuests($npc = 0) {
    $return = array();
    $quests = $this->cache->load("quests");
    if($quests === NULL) {
      $quests = $this->db->table("quests");
      foreach($quests as $quest) {
        $return[$quest->id] =
          new Quest($quest->id, $quest->name, $quest->introduction, $quest->end_text, $quest->reward_money,
            $quest->reward_xp, $quest->npc_start, $quest->npc_end, $quest->order, $quest->needed_item,
            $quest->item_amount, $quest->item_lose, $quest->cost_money, $quest->needed_level, $quest->needed_quest);
      }
      $this->cache->save("quests", $return);
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
   * Gets list of available quests from specified npc
   * 
   * @param int $npc Npc's id
   * @return type
   */
  function availableQuests($npc) {
    $return = $this->listOfQuests($npc);
    $playerQuests = $this->db->table("character_quests")
      ->where("character", $this->user->id);
    foreach($return as $key => $quest) {
      foreach($playerQuests as $pquest) {
        if($quest->id == $pquest->quest AND $pquest->progress > 2) {
          unset($return[$key]);
          continue 2;
        } elseif($quest->id == $pquest->quest AND $pquest->progress <= 2) {
          $quest->progress = true;
          continue 2;
        }
      }
      if($quest->needed_level > 0) {
        if($this->user->identity->level < $quest->needed_level) unset($return[$key]);
      } elseif($quest->needed_quest > 0) {
        if(!$this->isFinished($quest->id)) unset($return[$key]);
      }
    }
    return $return;
  }
  
  /**
   * Gets info about specified quest
   * 
   * @param int $id Quest's id
   * @return \HeroesofAbenez\Quest
   */
  function view($id) {
    $quests = $this->listOfQuests();
    $quest = Arrays::get($quests, $id, false);
    return $quest;
  }
  
  /**
   * Accept specified quest
   * 
   * @param int $id Quest's id
   * @param string $url
   * @return int Error code|1 on success
   */
  function accept($id, $url) {
    $quest = $this->db->table("quests")->get($id);
    if(!$quest) return 2;
    $row = $this->db->table("character_quests")
      ->where("character", $this->user->id)
      ->where("quest", $id);
    if($row->count("quest") > 0) return 3;
    $referer = $this->request->getReferer();
    if($referer === NULL) return 4;
    if($referer->path != $url) return 4;
    $data = array(
      "character" => $this->user->id, "quest" => $id
    );
    $result = $this->db->query("INSERT INTO character_quests", $data);
    if(!$result) return 5;
    return 1;
  }
  
  /**
   * Checks if the player accomplished specified quest's goals
   * 
   * @param \HeroesofAbenez\Quest $quest
   * @return bool
   */
  protected function isCompleted($quest) {
    $haveMoney = $haveItem = false;
    if($quest->cost_money > 0) {
      $char = $this->db->table("characters")->get($this->user->id);
      if($char->money >= $quest->cost_money) $haveMoney = true;
    } else {
      $haveMoney = true;
    }
    if($quest->needed_item > 0) {
      $itemRow = $this->db->table("character_items")
        ->where("character", $this->user->id)
        ->where("item", $quest->needed_item);
      if($itemRow->count("id") == 1) {
        foreach($itemRow as $item) { }
        if($item->amount >= $quest->item_amount) $haveItem = true;
      }
    } else {
      $haveItem = true;
    }
    return ($haveMoney AND $haveItem);
  }
  
  /**
   * Finish specified quest
   * 
   * @param int $id Quest's id
   * @param string $url
   * @return int Error code|1 on success
   */
  function finish($id, $url) {
    $quest = $this->db->table("quests")->get($id);
    if(!$quest) return 2;
    $row = $this->db->table("character_quests")
      ->where("character", $this->user->id)
      ->where("quest", $id);
    if($row->count("quest") === 0) return 3;
    foreach($row as $r) { }
    if($r->progress > 2) return 4;
    $referer = $this->request->getReferer();
    if($referer === NULL) return 5;
    if($referer->path != $url) return 5;
    if($this->isCompleted($quest)) {
      $wheres = array(
        "character" => $this->user->id, "quest" => $id
      );
      $data = array(
        "progress" => 3
      );
      $result = $this->db->query("UPDATE character_quests SET ? WHERE ?", $data, $wheres);
      if($result) {
        if($quest->item_lose) {
          $data2 = "amount=amount-{$quest->item_amount}";
          $wheres2 = array("character" => $this->user->id, "item" => $quest->needed_item);
          $result2 = $this->db->query("UPDATE character_items SET $data2 WHERE ?", $wheres2);
          if(!$result2) return 7;
        }
        if($quest->cost_money > 0) {
          $data3 = "money=money-{$quest->cost_money}";
          $where3 = array("id" => $this->user->id);
          $result3 = $this->db->query("UPDATE characters SET $data3 WHERE ?", $where3);
          if(!$result3) return 7;
        }
        return 1;
      } else {
        return 7;
      }
    }
    else return 6;
  }
  
  /**
   * Checks if the player finished specified quest
   * 
   * @param int $id Quest's id
   * @return bool
   */
  function isFinished($id) {
    $row = $this->db->table("character_quests")
      ->where("character", $this->user->id)
      ->where("quest", $id);
    if($row->count("*") === 0) return false;
    foreach($row as $r) { }
    if($r->progress > 2) return true;
    else return false;
  }
}
?>