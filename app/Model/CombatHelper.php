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
  /** @var ORM */
  protected $orm;
  
  public function __construct(Profile $profileModel, Item $itemModel, Skills $skillsModel, ORM $orm) {
    $this->profileModel = $profileModel;
    $this->itemModel = $itemModel;
    $this->skillsModel = $skillsModel;
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
   * @return \HeroesofAbenez\Combat\Equipment[]
   */
  protected function getPlayerEquipment(\HeroesofAbenez\Orm\Character $character): array {
    $equipment = [];
    foreach($character->items as $row) {
      if(!$row->worn) {
        continue;
      }
      $item = $row->item;
      $item->worn = true;
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
      "id", "name", "occupation", "level", "strength", "dexterity", "constitution", "intelligence",
      "charisma", "race", "specialization", "gender", "experience",
    ];
    foreach($stats as $stat) {
      if($character->$stat instanceof IEntity) {
        $data[$stat] = $character->$stat->id;
      } else {
        $data[$stat] = $character->$stat;
      }
    }
    $data["initiativeFormula"] = $character->occupation->initiative;
    $pet = $this->orm->pets->getActivePet($character);
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
    $skills = [];
    $skillRows = $this->orm->attackSkills->findByClassAndLevel($npc->occupation, $npc->level);
    foreach($skillRows as $skillRow) {
      $skills[] = new CharacterAttackSkill($skillRow->toDummy(), 0);
    }
    $skillRows = $this->orm->specialSkills->findByClassAndLevel($npc->occupation, $npc->level);
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
    return array_values(array_filter($skills, function(BaseCharacterSkill $value) {
      return ($value->level > 0);
    }));
  }

  /**
   * @return \HeroesofAbenez\Combat\Equipment[]
   */
  protected function getArenaNpcEquipment(\HeroesofAbenez\Orm\PveArenaOpponent $npc): array {
    $equipment = [];
    if(!is_null($npc->weapon)) {
      $npc->weapon->worn = true;
      $equipment[] = $npc->weapon->toCombatEquipment();
    }
    if(!is_null($npc->armor)) {
      $npc->armor->worn = true;
      $equipment[] = $npc->armor->toCombatEquipment();
    }
    return $equipment;
  }
  
  /**
   * Get data for specified npc
   *
   * @throws OpponentNotFoundException
   */
  public function getArenaNpc(int $id): Character {
    $data = [];
    $npc = $this->orm->arenaNpcs->getById($id);
    if(is_null($npc)) {
      throw new OpponentNotFoundException();
    }
    $stats = [
      "id", "name", "occupation", "level", "strength", "dexterity", "constitution", "intelligence",
      "charisma", "race", "gender",
    ];
    foreach($stats as $stat) {
      if($npc->$stat instanceof IEntity) {
        $data[$stat] = $npc->$stat->id;
      } else {
        $data[$stat] = $npc->$stat;
      }
    }
    $data["id"] = "pveArenaNpc" . $npc->id;
    $occupation = $npc->occupation;
    $data["initiativeFormula"] = $occupation->initiative;
    $equipment = $this->getArenaNpcEquipment($npc);
    $skills = $this->getArenaNpcSkills($npc);
    $npc = new Character($data, $equipment, [], $skills);
    return $npc;
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
}
?>