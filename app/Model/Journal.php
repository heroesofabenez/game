<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\JournalQuest,
    HeroesofAbenez\Entities\Pet as PetEntity,
    HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\PetTypeDummy;

/**
 * Journal Model
 *
 * @author Jakub Konečný
 */
class Journal {
  use \Nette\SmartObject;
  
  /** @var \Nette\Security\User */
  protected $user;
  /** @var ORM */
  protected $orm;
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
  
  function __construct(\Nette\Security\User $user, ORM $orm, \Nette\Database\Context $db, Quest $questModel, Location $locationModel, Guild $guildModel, Pet $petModel, Equipment $equipmentModel) {
    $this->user = $user;
    $this->db = $db;
    $this->orm = $orm;
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
  function basic(): array {
    $character = $this->orm->characters->getById($this->user->id);
    $stage = $this->locationModel->getStage($character->currentStage);
    $return = [
      "name" => $character->name, "gender" => $character->gender, "race" => $character->race->id,
      "occupation" => $character->occupation->id,
      "specialization" => ($character->specialization) ? $character->specialization->id : NULL,
      "level" => $character->level, "whiteKarma" => $character->whiteKarma,
      "neutralKarma" => $character->neutralKarma, "darkKarma" => $character->darkKarma,
      "experiences" => $character->experience, "description" => $character->description,
      "stageName" => $stage->name, "areaName" => $this->locationModel->getAreaName($stage->area)
    ];
    if($character->guild->id > 0) {
      $return["guild"] = $this->guildModel->getGuildName($character->guild->id);
      $return["guildRank"] = $character->guildrank->id;
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
  function inventory(): array {
    $return = [];
    $char = $this->orm->characters->getById($this->user->id);
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
  function pets(): array {
    $return = [];
    $pets = $this->orm->pets->findByOwner($this->user->id);
    foreach($pets as $pet) {
      $type = new PetTypeDummy($pet->type);
      $return[] = new PetEntity($pet->id, $type, (($pet->name) ? $pet->name : ""), $pet->deployed);
    }
    return $return;
  }
  
   /**
   * Gets character's quests
   * 
   * @return JournalQuest[]
   */
  function quests(): array {
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