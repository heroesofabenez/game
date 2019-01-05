<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Combat\Character;
use HeroesofAbenez\Combat\CharacterAttackSkill;
use HeroesofAbenez\Combat\CharacterSpecialSkill;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\ArenaFightCount;
use Nextras\Orm\Entity\IEntity;
use HeroesofAbenez\Combat\BaseCharacterSkill;
use Nexendrie\Utils\Collection;
use HeroesofAbenez\Combat\Equipment;

/**
 * Combat Helper
 *
 * @author Jakub Konečný
 */
final class CombatHelper {
  use \Nette\SmartObject;
  
  /** @var Profile */
  protected $profileModel;
  /** @var Item */
  protected $itemModel;
  /** @var Skills */
  protected $skillsModel;
  /** @var CharacterBuilder */
  protected $cb;
  /** @var ORM */
  protected $orm;
  
  public function __construct(Profile $profileModel, Item $itemModel, Skills $skillsModel, ORM $orm, CharacterBuilder $cb) {
    $this->profileModel = $profileModel;
    $this->itemModel = $itemModel;
    $this->skillsModel = $skillsModel;
    $this->cb = $cb;
    $this->orm = $orm;
  }
  
  /**
   * @return BaseCharacterSkill[]
   */
  protected function getPlayerSkills(\HeroesofAbenez\Orm\Character $character): array {
    $skills = [];
    foreach($character->attackSkills as $skill) {
      $skills[] = $skill->toCombatSkill();
    }
    foreach($character->specialSkills as $skill) {
      $skills[] = $skill->toCombatSkill();
    }
    return $skills;
  }

  /**
   * @return Equipment[]
   */
  protected function getPlayerEquipment(\HeroesofAbenez\Orm\Character $character): array {
    $equipment = [];
    $items = $character->items->get()->findBy(["worn" => true]);
    foreach($items as $item) {
      $equipment[] = $item->toCombatEquipment();
    }
    return $equipment;
  }

  /**
   * Get data for specified player
   *
   * @throws OpponentNotFoundException
   */
  public function getPlayer(int $id): Character {
    $data = $pets = [];
    $character = $this->orm->characters->getById($id);
    if(is_null($character)) {
      throw new OpponentNotFoundException();
    }
    $stats = [
      "id", "name", "level", "strength", "dexterity", "constitution", "intelligence",
      "charisma", "race", "specialization", "gender", "experience",
    ];
    foreach($stats as $stat) {
      if($character->$stat instanceof IEntity) {
        $data[$stat] = $character->$stat->id;
      } else {
        $data[$stat] = $character->$stat;
      }
    }
    $data["occupation"] = $character->class->id;
    $data["initiativeFormula"] = $character->class->initiative;
    $pet = $character->activePet;
    if(!is_null($pet)) {
      $pets[] = $pet->toCombatPet();
    }
    $skills = $this->getPlayerSkills($character);
    $equipment = $this->getPlayerEquipment($character);
    $player = new Character($data, $equipment, $pets, $skills);
    return $player;
  }
  
  /**
   * @return BaseCharacterSkill[]
   */
  protected function getArenaNpcSkills(\HeroesofAbenez\Orm\PveArenaOpponent $npc): array {
    if($npc->level === 1) {
      return [];
    }
    $skills = new class extends Collection {
      /** @var string */
      protected $class = BaseCharacterSkill::class;
    };
    $skillRows = $this->orm->attackSkills->findByClassAndLevel($npc->class, $npc->level);
    foreach($skillRows as $skillRow) {
      $skills[] = new CharacterAttackSkill($skillRow->toDummy(), 0);
    }
    $skillRows = $this->orm->specialSkills->findByClassAndLevel($npc->class, $npc->level);
    foreach($skillRows as $skillRow) {
      $skills[] = new CharacterSpecialSkill($skillRow->toDummy(), 0);
    }
    $skillPoints = $npc->level - 1;
    for($i = 1; $skillPoints > 0; $i++) {
      foreach($skills as $skill) {
        if($skillPoints < 1) {
          break;
        }
        if($skill->level + 1 <= $skill->skill->levels) {
          $skill->level++;
        }
        $skillPoints--;
      }
    }
    return $skills->getItems(["level>" => 0]);
  }

  /**
   * @return Equipment[]
   */
  protected function getArenaNpcEquipment(\HeroesofAbenez\Orm\PveArenaOpponent $npc): array {
    $equipment = new class extends Collection {
      /** @var string */
      protected $class = Equipment::class;
    };
    foreach($npc->equipment as $eq) {
      $eq->item->worn = true;
      $equipment[] = $eq->item->toCombatEquipment();
    }
    if(!$equipment->hasItems(["slot" => Equipment::SLOT_WEAPON]) AND !is_null($npc->weapon)) {
      $npc->weapon->worn = true;
      $equipment[] = $npc->weapon->toCombatEquipment();
    }
    if(!$equipment->hasItems(["slot" => Equipment::SLOT_ARMOR]) AND !is_null($npc->armor)) {
      $npc->armor->worn = true;
      $equipment[] = $npc->armor->toCombatEquipment();
    }
    return $equipment->toArray();
  }
  
  /**
   * Get data for specified npc
   *
   * @throws OpponentNotFoundException
   */
  public function getArenaNpc(int $id): Character {
    $npc = $this->orm->arenaNpcs->getById($id);
    if(is_null($npc)) {
      throw new OpponentNotFoundException();
    }
    $data = $this->cb->create($npc->class, $npc->race, $npc->level, $npc->specialization);
    $stats = [
      "name", "level", "race", "gender", "specialization",
    ];
    foreach($stats as $stat) {
      if($npc->$stat instanceof IEntity) {
        $data[$stat] = $npc->$stat->id;
      } else {
        $data[$stat] = $npc->$stat;
      }
    }
    $data["id"] = "pveArenaNpc" . $npc->id;
    $class = $npc->class;
    $data["initiativeFormula"] = $class->initiative;
    $data["occupation"] = $class->id;
    $equipment = $this->getArenaNpcEquipment($npc);
    $skills = $this->getArenaNpcSkills($npc);
    $npc = new Character($data, $equipment, [], $skills);
    return $npc;
  }

  /**
   * Get data for specified npc
   *
   * @throws OpponentNotFoundException
   */
  public function getCommonNpc(int $id): Character {
    $npc = $this->orm->npcs->getById($id);
    if(is_null($npc)) {
      throw new OpponentNotFoundException();
    }
    $data = $this->cb->create($npc->class, $npc->race, $npc->level, $npc->specialization);
    $stats = [
      "name", "level", "race", "specialization",
    ];
    foreach($stats as $stat) {
      if($npc->$stat instanceof IEntity) {
        $data[$stat] = $npc->$stat->id;
      } else {
        $data[$stat] = $npc->$stat;
      }
    }
    $data["id"] = "commonNpc" . $npc->id;
    $class = $npc->class;
    $data["initiativeFormula"] = $class->initiative;
    $data["occupation"] = $class->id;
    return new Character($data);
  }
  
  /**
   * Get amount of fights a player has fought today in arena
   */
  public function getNumberOfTodayArenaFights(int $uid): int {
    $row = $this->orm->arenaFightsCount->getByCharacterAndDay($uid, date("d.m.Y"));
    if(is_null($row)) {
      return 0;
    }
    return $row->amount;
  }
  
  /**
   * Increase amount of a player's fights in arena
   */
  public function bumpNumberOfTodayArenaFights(int $uid): void {
    $day = date("d.m.Y");
    $row = $this->orm->arenaFightsCount->getByCharacterAndDay($uid, $day);
    if(is_null($row)) {
      $row = new ArenaFightCount();
      $this->orm->arenaFightsCount->attach($row);
      $row->character = $uid;
      $row->day = $day;
      $row->amount = 0;
    }
    $row->amount++;
    $this->orm->arenaFightsCount->persistAndFlush($row);
  }

  public function wearOutEquipment(\HeroesofAbenez\Combat\CombatBase $combat): void {
    /** @var Character[] $characters */
    $characters = array_merge($combat->team1->toArray(), $combat->team2->toArray());
    foreach($characters as $character) {
      if(!is_int($character->id)) {
        continue;
      }
      foreach($character->equipment as $equipment) {
        if(!$equipment->worn) {
          continue;
        }
        /** @var \HeroesofAbenez\Orm\CharacterItem $item */
        $item = $this->orm->characterItems->getByCharacterAndItem($character->id, $equipment->id);
        $item->durability--;
        $this->orm->characterItems->persist($item);
      }
    }
    $this->orm->characterItems->flush();
  }
}
?>