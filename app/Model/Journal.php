<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\JournalQuest,
    HeroesofAbenez\Orm\Pet as PetEntity,
    HeroesofAbenez\Orm\Model as ORM,
    Nextras\Orm\Collection\ICollection;

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
  
  function __construct(\Nette\Security\User $user, ORM $orm, Quest $questModel, Location $locationModel, Guild $guildModel, Pet $petModel, Equipment $equipmentModel) {
    $this->user = $user;
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
      $return["guild"] = $character->guild->name;
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
    foreach($char->items as $item) {
      $return["items"][] = (object) ["id" => $item->item->id, "name" => $item->item->name, "amount" => $item->amount];
    }
    $return["equipments"] = [];
    foreach($char->equipment as $equipment) {
      $i = $equipment->item;
      $return["equipments"][] = (object) ["id" => $i->id, "name" => $i->name, "amount" => $equipment->amount, "worn" => (bool) $equipment->worn, "eqid" => $equipment->id];
    }
    return $return;
  }
  
  /**
   * Gets character's pets
   * 
   * @return ICollection|PetEntity[]
   */
  function pets(): ICollection {
    return $this->orm->pets->findByOwner($this->user->id);
  }
  
   /**
   * Gets character's quests
   * 
   * @return JournalQuest[]
   */
  function quests(): array {
    $return = [];
    $quests = $this->orm->characterQuests->findByCharacter($this->user->id);
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