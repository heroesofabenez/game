<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\JournalQuest,
    HeroesofAbenez\Entities\Pet;

/**
 * Journal Model
 *
 * @author Jakub Konečný
 */
class Journal extends \Nette\Object {
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \HeroesofAbenez\Model\Quest */
  protected $questModel;
  /** @var \HeroesofAbenez\Model\Location */
  protected $locationModel;
  /** @var \HeroesofAbenez\Model\Guild */
  protected $guildModel;
  
  /**
   * @param \Nette\Security\User $user
   * @param \Nette\Database\Context $db
   */
  function __construct(\Nette\Security\User $user,\Nette\Database\Context $db) {
    $this->user = $user;
    $this->db = $db;
  }
  
  function setQuestModel(Quest $questModel) {
    $this->questModel = $questModel;
  }
  
  function setLocationModel(Location $locationModel) {
    $this->locationModel = $locationModel;
  }
  
  function setGuildModel(Guild $guildModel) {
    $this->guildModel = $guildModel;
  }
  
  /**
   * Gets basic info for character's journal
   * 
   * @return array
   */
  function basic() {
    $user = $this->user->identity;
    $character = $this->db->table("characters")->get($user->id);
    $stage = $this->locationModel->getStage($user->stage);
    $return = array(
      "name" => $user->name, "gender" => $user->gender, "race" => $user->race,
      "occupation" => $user->occupation, "specialization" => $user->specialization,
      "level" => $user->level, "whiteKarma" => $user->white_karma,
      "neutralKarma" => $user->neutral_karma, "darkKarma" => $user->dark_karma,
      "experiences" => $character->experience, "description" => $character->description,
      "stageName" => $stage->name, "areaName" => $this->locationModel->getAreaName($stage->area)
    );
    if($user->guild > 0) {
      $return["guild"] = $this->guildModel->getGuildName($user->guild);
      $return["guildRank"] = $character->guildrank;
    } else {
      $return["guild"] = false;
    }
    return $return;
  }
  
  /**
   * Gets character's inventory
   * 
   * @return array
   */
  function inventory() {
    $return = array();
    $uid = $this->user->id;
    $char = $this->db->table("characters")->get($uid);
    $return["money"] = $char->money;
    $return["items"] = array();
    $items = $this->db->table("character_items")
      ->where("character", $this->user->id);
    foreach($items as $item) {
      $i = $this->db->table("items")->get($item->item);
      $return["items"][] = (object) array("id" => $i->id, "name" => $i->name, "amount" => $item->amount);
    }
    $return["equipments"] = array();
    $equipments = $this->db->table("character_equipment")
      ->where("character", $this->user->id);
    foreach($equipments as $equipment) {
      $i = $this->db->table("equipment")->get($equipment->item);
      $return["equipments"][] = (object) array("id" => $i->id, "name" => $i->name, "amount" => $equipment->amount);
    }
    return $return;
  }
  
  /**
   * Gets character's pets
   * 
   * @return array
   */
  function pets() {
    $return = array();
    $uid = $this->user->id;
    $pets = $this->db->table("pets")
      ->where("owner", $uid);
    foreach($pets as $pet) {
      $type = $this->db->table("pet_types")->get($pet->id);
      $return[] = new Pet($pet->id, $type->name, $pet->name, $pet->bonus_stat, $pet->bonus_value, $pet->deployed);
    }
    return $return;
  }
  
   /**
   * Gets character's quests
   * 
   * @return array
   */
  function quests() {
    $return = array();
    $uid = $this->user->id;
    $quests = $this->db->table("character_quests")
      ->where("character", $uid);
    foreach($quests as $row) {
      if($row->progress < 3) {
        $quest = $this->questModel->view($row->id);
        $return[] = new JournalQuest($quest->id, $quest->name);
      }
    }
    return $return;
  }
}
?>