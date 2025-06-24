<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Combat\Character;
use HeroesofAbenez\Combat\EquipmentCollection;
use HeroesofAbenez\Combat\Team;
use HeroesofAbenez\Orm\CharacterItem;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\ArenaFightCount;
use HeroesofAbenez\Combat\BaseCharacterSkill;
use HeroesofAbenez\Combat\Equipment;
use Nextras\Orm\Collection\ICollection;

/**
 * Combat Helper
 *
 * @author Jakub Konečný
 */
final class CombatHelper {
  use \Nette\SmartObject;

  private CharacterBuilder $cb;
  private ORM $orm;
  
  public function __construct(ORM $orm, CharacterBuilder $cb) {
    $this->cb = $cb;
    $this->orm = $orm;
  }
  
  /**
   * @return BaseCharacterSkill[]
   */
  private function getPlayerSkills(\HeroesofAbenez\Orm\Character $character): array {
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
  private function getPlayerEquipment(\HeroesofAbenez\Orm\Character $character): array {
    $equipment = [];
    /** @var ICollection|CharacterItem[] $items */
    $items = $character->items->toCollection()->findBy(["worn" => true, ]);
    foreach($items as $item) {
      $e = $item->toCombatEquipment();
      if($e !== null) {
        $equipment[] = $e;
      }
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
    if($character === null) {
      throw new OpponentNotFoundException();
    }
    $stats = [
      "id", "name", "level", "strength", "dexterity", "constitution", "intelligence",
      "charisma", "race", "specialization", "gender", "experience",
    ];
    foreach($stats as $stat) {
      if(is_scalar($character->$stat) || $character->$stat === null) {
        $data[$stat] = $character->$stat;
      } else {
        $data[$stat] = $character->$stat->id;
      }
    }
    $data["occupation"] = $character->class->name;
    $data["specialization"] = ($character->specialization !== null) ? $character->specialization->name : "";
    $data["initiativeFormula"] = $character->class->initiative;
    $pet = $character->activePet;
    if($pet !== null) {
      $pets[] = $pet->toCombatPet();
    }
    $skills = $this->getPlayerSkills($character);
    $equipment = $this->getPlayerEquipment($character);
    $player = new Character($data, $equipment, $pets, $skills);
    return $player;
  }

  /**
   * @return Equipment[]
   */
  private function getArenaNpcEquipment(\HeroesofAbenez\Orm\PveArenaOpponent $npc): array {
    $equipment = new EquipmentCollection();
    foreach($npc->equipment as $eq) {
      $eq->item->worn = true;
      $e = $eq->item->toCombatEquipment();
      if($e !== null) {
        $equipment[] = $e;
      }
    }
    if(!$equipment->hasItems(["slot" => Equipment::SLOT_WEAPON]) && $npc->weapon !== null) {
      $npc->weapon->worn = true;
      $e = $npc->weapon->toCombatEquipment();
      if($e !== null) {
        $equipment[] = $e;
      }
    }
    if(!$equipment->hasItems(["slot" => Equipment::SLOT_ARMOR]) && $npc->armor !== null) {
      $npc->armor->worn = true;
      $e = $npc->armor->toCombatEquipment();
      if($e !== null) {
        $equipment[] = $e;
      }
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
    if($npc === null) {
      throw new OpponentNotFoundException();
    }
    $data = $this->cb->create($npc->class, $npc->race, $npc->level, $npc->specialization);
    $stats = [
      "name", "level", "race", "gender",
    ];
    foreach($stats as $stat) {
      if(is_scalar($npc->$stat) || $npc->$stat === null) {
        $data[$stat] = $npc->$stat;
      } else {
        $data[$stat] = $npc->$stat->id;
      }
    }
    $data["id"] = "pveArenaNpc" . $npc->id;
    $data["initiativeFormula"] = $npc->class->initiative;
    $data["occupation"] = $npc->class->name;
    $data["specialization"] = ($npc->specialization !== null) ? $npc->specialization->name : "";
    $equipment = $this->getArenaNpcEquipment($npc);
    $npc = new Character($data, $equipment, [], $npc->skills);
    return $npc;
  }

  /**
   * Get data for specified npc
   *
   * @throws OpponentNotFoundException
   */
  public function getCommonNpc(int $id): Character {
    $npc = $this->orm->npcs->getById($id);
    if($npc === null) {
      throw new OpponentNotFoundException();
    }
    $data = $this->cb->create($npc->class, $npc->race, $npc->level, $npc->specialization);
    $stats = [
      "name", "level", "race",
    ];
    foreach($stats as $stat) {
      if(is_scalar($npc->$stat) || $npc->$stat === null) {
        $data[$stat] = $npc->$stat;
      } else {
        $data[$stat] = $npc->$stat->id;
      }
    }
    $data["id"] = "commonNpc" . $npc->id;
    $data["initiativeFormula"] = $npc->class->initiative;
    $data["occupation"] = $npc->class->name;
    $data["specialization"] = ($npc->specialization !== null) ? $npc->specialization->name : "";
    return new Character($data);
  }
  
  /**
   * Get amount of fights a player has fought today in arena
   */
  public function getNumberOfTodayArenaFights(int $uid): int {
    $row = $this->orm->arenaFightsCount->getByCharacterAndDay($uid, date("d.m.Y"));
    if($row === null) {
      return 0;
    }
    return $row->amount;
  }
  
  /**
   * Increase amount of a player's fights in arena
   */
  public function bumpNumberOfTodayArenaFights(int $uid, bool $won): void {
    $day = date("d.m.Y");
    $row = $this->orm->arenaFightsCount->getByCharacterAndDay($uid, $day);
    if($row === null) {
      $row = new ArenaFightCount();
      $this->orm->arenaFightsCount->attach($row);
      $row->character = $uid;
      $row->day = $day;
      $row->amount = 0;
      $row->won = 0;
    }
    $row->amount++;
    if($won) {
      $row->won++;
    }
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

  /**
   * @return string[]
   */
  private function getHealerSpecializations(): array {
    return ["paladin", "priest", ];
  }

  public function getHealers(Team $team1, Team $team2): Team {
    $healers = new Team("healers");
    $healerSpecializations = $this->getHealerSpecializations();
    /** @var Character[] $characters */
    $characters = array_merge($team1->toArray(), $team2->toArray());
    foreach($characters as $character) {
      if(in_array($character->specialization, $healerSpecializations, true)) {
        $healers[] = $character;
      }
    }
    return $healers;
  }
}
?>