<?php
namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\Quest as QuestEntity;

/**
 * Quest Model
 * 
 * @author Jakub Konečný
 */
class Quest extends \Nette\Object {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Security\User */
  protected $user;
  
  /**
   * @param \Nette\Caching\Cache $cache
   * @param \Nette\Database\Context $db
   * @param \Nette\Security\User $user
   */
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db, \Nette\Security\User $user) {
    $this->db = $db;
    $this->cache = $cache;
    $this->user = $user;
  }
  
  /**
   * Gets list of quests
   * 
   * @param int $npc Return quests only from certain npc, 0 = all npcs
   * @return QuestEntity[]
   */
  function listOfQuests($npc = 0) {
    $return = array();
    $quests = $this->cache->load("quests");
    if($quests === NULL) {
      $quests = $this->db->table("quests");
      foreach($quests as $quest) {
        $return[$quest->id] = new QuestEntity($quest);
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
   * Gets info about specified quest
   * 
   * @param int $id Quest's id
   * @return \HeroesofAbenez\Entities\Quest
   */
  function view($id) {
    $quests = $this->listOfQuests();
    $quest = Arrays::get($quests, $id, false);
    return $quest;
  }
  
  /**
   * Get quest's status
   * 
   * @param int $id Quest's id
   * @return int
   */
  function status($id) {
    $row = $this->db->table("character_quests")
      ->where("character", $this->user->id)
      ->where("quest", $id);
    if($row->count() === 0) return 0;
    $r = $row->fetch();
    return $r->progress;
  }
  
  /**
   * Checks if the player finished specified quest
   * 
   * @param int $id Quest's id
   * @return bool
   */
  function isFinished($id) {
    $status = $this->status($id);
    if($status > 2) return true;
    else return false;
  }
}
?>