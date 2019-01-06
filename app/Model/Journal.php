<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Pet as PetEntity;
use HeroesofAbenez\Orm\Model as ORM;
use Nextras\Orm\Collection\ICollection;
use Nette\Localization\ITranslator;

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
  /** @var Item */
  protected $itemModel;
  /** @var ITranslator */
  protected $translator;
  
  public function __construct(\Nette\Security\User $user, ORM $orm, Quest $questModel, Location $locationModel, Guild $guildModel, Pet $petModel, Item $itemModel, ITranslator $translator) {
    $this->user = $user;
    $this->orm = $orm;
    $this->questModel = $questModel;
    $this->locationModel = $locationModel;
    $this->guildModel = $guildModel;
    $this->petModel = $petModel;
    $this->itemModel = $itemModel;
    $this->translator = $translator;
  }

  
  /**
   * Gets basic info for character's journal
   */
  public function basic(): array {
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    $stage = $character->currentStage;
    $stageName = $this->translator->translate("stages.{$stage->id}.name");
    $areaName = $this->translator->translate("areas.{$stage->area->id}.name");
    $return = [
      "name" => $character->name, "gender" => $character->gender, "race" => $character->race->id,
      "class" => $character->class->id,
      "specialization" => (!is_null($character->specialization)) ? $character->specialization->id : null,
      "level" => $character->level, "whiteKarma" => $character->whiteKarma, "darkKarma" => $character->darkKarma,
      "experiences" => $character->experience, "stageName" => $stageName, "areaName" => $areaName,
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
      $i = $item->item;
      $return["items"][] = (object) [
        "id" => $i->id, "name" => $i->name, "amount" => $item->amount, "worn" => $item->worn, "eqid" => $item->id,
        "durability" => $item->durability, "maxDurability" => $item->maxDurability, "repairPrice" => $item->repairPrice,
      ];
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
   * @return int[]
   */
  public function quests(): array {
    $return = [];
    $quests = $this->orm->characterQuests->findByCharacter($this->user->id);
    foreach($quests as $quest) {
      if($quest->progress < \HeroesofAbenez\Orm\CharacterQuest::PROGRESS_FINISHED) {
        $return[] = $quest->quest->id;
      }
    }
    return $return;
  }
}
?>