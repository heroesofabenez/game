<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\JournalQuest,
    HeroesofAbenez\Entities\Pet as PetEntity;

/**
 * Journal Model
 *
 * @author Jakub Konečný
 */
class Journal {
  use \Nette\SmartObject;
  
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var Quest */
  protected $questModel;
  /** @var Location */
  protected $locationModel;
  /** @var Guild */
  protected $guildModel;
  /** @var Pet */
  protected $petModel;
  /** @var Equipment */
  protected $equipmentModel;
  
  /**
   * @param \Nette\Security\User $user
   * @param \Nette\Database\Context $db
   * @param Quest $questModel
   * @param Location $locationModel
   * @param Guild $guildModel
   * @param Pet $petModel
   * @param Equipment $equipmentModel
   */
  function __construct(\Nette\Security\User $user, \Nette\Database\Context $db, Quest $questModel, Location $locationModel, Guild $guildModel, Pet $petModel, Equipment $equipmentModel) {
    $this->user = $user;
    $this->db = $db;
    $this->questModel = $questModel;
    $this->locationModel = $locationModel;
    $this->guildModel = $guildModel;
    $this->petModel = $petModel;
    $this->equipmentModel = $equipmentModel;
  }

  
  /**
   * Gets basic info for character's journal
   * 
   * @return array
   */
  function basic() {
    $character = $this->db->table("characters")->get($this->user->id);
    $stage = $this->locationModel->getStage($character->current_stage);
    $return = [
      "name" => $character->name, "gender" => $character->gender, "race" => $character->race,
      "occupation" => $character->occupation, "specialization" => $character->specialization,
      "level" => $character->level, "whiteKarma" => $character->white_karma,
      "neutralKarma" => $character->neutral_karma, "darkKarma" => $character->dark_karma,
      "experiences" => $character->experience, "description" => $character->description,
      "stageName" => $stage->name, "areaName" => $this->locationModel->getAreaName($stage->area)
    ];
    if($character->guild > 0) {
      $return["guild"] = $this->guildModel->getGuildName($character->guild);
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
    $return = [];
    $uid = $this->user->id;
    $char = $this->db->table("characters")->get($uid);
    $return["money"] = $char->money;
    $return["items"] = [];
    $items = $this->db->table("character_items")
      ->where("character", $this->user->id);
    foreach($items as $item) {
      $i = $this->db->table("items")->get($item->item);
      $return["items"][] = (object) ["id" => $i->id, "name" => $i->name, "amount" => $item->amount];
    }
    $return["equipments"] = [];
    $equipments = $this->db->table("character_equipment")
      ->where("character", $this->user->id);
    foreach($equipments as $equipment) {
      $i = $this->equipmentModel->view($equipment->item);
      $return["equipments"][] = (object) ["id" => $i->id, "name" => $i->name, "amount" => $equipment->amount, "worn" => (bool) $equipment->worn, "eqid" => $equipment->id];
    }
    return $return;
  }
  
  /**
   * Gets character's pets
   * 
   * @return PetEntity[]
   */
  function pets() {
    $return = [];
    $uid = $this->user->id;
    $pets = $this->db->table("pets")
      ->where("owner", $uid);
    foreach($pets as $pet) {
      $type = $this->petModel->viewType($pet->type);
      $return[] = new PetEntity($pet->id, $type, $pet->name, $pet->deployed);
    }
    return $return;
  }
  
   /**
   * Gets character's quests
   * 
   * @return JournalQuest[]
   */
  function quests() {
    $return = [];
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