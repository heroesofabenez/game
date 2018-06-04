<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Pet as PetEntity;
use HeroesofAbenez\Orm\Model as ORM;
use Nextras\Orm\Collection\ICollection;

/**
 * Journal Model
 *
 * @author Jakub Konečný
 */
final class Journal {
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
  
  public function __construct(\Nette\Security\User $user, ORM $orm, Quest $questModel, Location $locationModel, Guild $guildModel, Pet $petModel, Equipment $equipmentModel) {
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
   */
  public function basic(): array {
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    $stage = $character->currentStage;
    $return = [
      "name" => $character->name, "gender" => $character->gender, "race" => $character->race->id,
      "occupation" => $character->occupation->id,
      "specialization" => (!is_null($character->specialization)) ? $character->specialization->id : null,
      "level" => $character->level, "whiteKarma" => $character->whiteKarma,
      "neutralKarma" => $character->neutralKarma, "darkKarma" => $character->darkKarma,
      "experiences" => $character->experience, "description" => $character->description,
      "stageName" => $character->currentStage->name, "areaName" => $stage->area->name
    ];
    $return["guild"] = false;
    if(!is_null($character->guild)) {
      $return["guild"] = $character->guild->name;
      $return["guildRank"] = $character->guildrank->id;
    }
    return $return;
  }
  
  /**
   * Gets character's inventory
   */
  public function inventory(): array {
    $return = [];
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    $return["money"] = $character->money;
    $return["items"] = [];
    foreach($character->items as $item) {
      $return["items"][] = (object) ["id" => $item->item->id, "name" => $item->item->name, "amount" => $item->amount];
    }
    $return["equipments"] = [];
    foreach($character->equipment as $equipment) {
      $i = $equipment->item;
      $return["equipments"][] = (object) ["id" => $i->id, "name" => $i->name, "amount" => $equipment->amount, "worn" => $equipment->worn, "eqid" => $equipment->id];
    }
    return $return;
  }
  
  /**
   * Gets character's pets
   * 
   * @return ICollection|PetEntity[]
   */
  public function pets(): ICollection {
    return $this->orm->pets->findByOwner($this->user->id);
  }
  
   /**
   * Gets character's quests
   * 
   * @return \stdClass[]
   */
  public function quests(): array {
    $return = [];
    $quests = $this->orm->characterQuests->findByCharacter($this->user->id);
    foreach($quests as $quest) {
      if($quest->progress < 3) {
        $return[] = (object) [
          "id" => $quest->quest->id, "name" => $quest->quest->name
        ];
      }
    }
    return $return;
  }
}
?>