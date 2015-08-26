<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\JournalQuest,
    HeroesofAbenez\Entities\Pet as PetEntity;

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
  /** @var \HeroesofAbenez\Model\Pet */
  protected $petModel;
  /** @var \HeroesofAbenez\Model\Equipment */
  protected $equipmentModel;
  
  /**
   * @param \Nette\Security\User $user
   * @param \Nette\Database\Context $db
   * @param \HeroesofAbenez\Model\Quest $questModel
   * @param \HeroesofAbenez\Model\Location $locationModel
   * @param \HeroesofAbenez\Model\Guild $guildModel
   * @param \HeroesofAbenez\Model\Pet $petModel
   */
  function __construct(\Nette\Security\User $user, \Nette\Database\Context $db, \HeroesofAbenez\Model\Quest $questModel, \HeroesofAbenez\Model\Location $locationModel, \HeroesofAbenez\Model\Guild $guildModel, \HeroesofAbenez\Model\Pet $petModel) {
    $this->user = $user;
    $this->db = $db;
    $this->questModel = $questModel;
    $this->locationModel = $locationModel;
    $this->guildModel = $guildModel;
    $this->petModel = $petModel;
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
  
  function setEquipmentModel(\HeroesofAbenez\Model\Equipment $equipmentModel) {
    $this->equipmentModel = $equipmentModel;
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
      $i = $this->equipmentModel->view($equipment->item);
      $return["equipments"][] = (object) array("id" => $i->id, "name" => $i->name, "amount" => $equipment->amount);
    }
    return $return;
  }
  
  /**
   * Gets character's pets
   * 
   * @return PetEntity[]
   */
  function pets() {
    $return = array();
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