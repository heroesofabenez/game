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
  /** @var bool */
  public $progress = false;
  
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
          new Quest($quest->id, $quest->name, $quest->introduction, $quest->middle_text,
            $quest->end_text, $quest->reward_money, $quest->reward_xp, $quest->npc_start,
            $quest->npc_end, $quest->order, $quest->needed_item, $quest->item_amount, $quest->item_lose);
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
    foreach($playerQuests as $pquest) {
      foreach($return as $key => $quest) {
        if($quest->id == $pquest->quest AND $pquest->progress > 2) unset($return[$key]);
        elseif($quest->id == $pquest->quest AND $pquest->progress <= 2) $quest->progress = true;
      }
    }
    return $return;
  }
  
  /**
   * Gets info about specified quest
   * 
   * @param int $id Quest's id
   * @return \HeroesofAbenez\NPC
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
    if($this->request->getReferer()->path != $url) return 4;
    $data = array(
      "character" => $this->user->id, "quest" => $id
    );
    $result = $this->db->query("INSERT INTO character_quests", $data);
    if(!$result) return 5;
    return 1;
  }
}
?>