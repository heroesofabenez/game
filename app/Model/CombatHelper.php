<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Character,
    HeroesofAbenez\Orm\CharacterAttackSkillDummy,
    HeroesofAbenez\Orm\SkillAttackDummy,
    HeroesofAbenez\Orm\CharacterSpecialSkillDummy,
    HeroesofAbenez\Orm\SkillSpecialDummy,
    HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\ArenaFightCount,
    Nextras\Orm\Entity\IEntity;

/**
 * Combat Helper
 *
 * @author Jakub Konečný
 */
class CombatHelper {
  use \Nette\SmartObject;
  
  /** @var Profile */
  protected $profileModel;
  /** @var Equipment */
  protected $equipmentModel;
  /** @var Skills */
  protected $skillsModel;
  /** @var ORM */
  protected $orm;
  
  function __construct(Profile $profileModel, Equipment $equipmentModel, Skills $skillsModel, ORM $orm) {
    $this->profileModel = $profileModel;
    $this->equipmentModel = $equipmentModel;
    $this->skillsModel = $skillsModel;
    $this->orm = $orm;
  }
  
  /**
   * Get initiative formula for given class
   * 
   * @param int $classId
   * @return string
   */
  function getInitiativeFormula(int $classId): string {
    $class = $this->profileModel->getClass($classId);
    if(is_null($class)) {
      return "0";
    } else {
      return $class->initiative;
    }
  }
  
  /**
   * Get data for specified player
   *
   * @param int $id Player's id
   * @return Character
   * @throws OpponentNotFoundException
   */
  function getPlayer(int $id): Character {
    $data = $equipment = $pets = [];
    $character = $this->orm->characters->getById($id);
    if(is_null($character)) {
      throw new OpponentNotFoundException;
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
    $data["initiativeFormula"] = $this->getInitiativeFormula($data["occupation"]);
    $pet = $this->orm->pets->getActivePet($character);
    if(!is_null($pet)) {
      $pets[] = $pet;
    }
    foreach($character->equipment as $row) {
      if(!$row->worn) {
        continue;
      }
      $item = $this->equipmentModel->view($row->item->id);
      $item->worn = true;
    }
    $skills = $this->skillsModel->getPlayerSkills($id);
    $player = new Character($data, $equipment, $pets, $skills);
    return $player;
  }
  
  /**
   * @param array $data
   * @param array $skills
   * @return void
   */
  protected function getArenaNpcSkillsLevels(array $data, array &$skills): void {
    if($data["level"] < 2) {
      $skills = [$skills[0]];
    }
    $skillPoints = $data["level"];
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
  }
  
  /**
   * Get data for specified npc
   * 
   * @param int $id Npc's id
   * @return Character
   * @throws OpponentNotFoundException
   */
  function getArenaNpc($id): Character {
    $data = [];
    $npc = $this->orm->arenaNpcs->getById($id);
    if(is_null($npc)) {
      throw new OpponentNotFoundException;
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
    $data["initiativeFormula"] = $this->getInitiativeFormula($data["occupation"]);
    $skills = $equipment = [];
    $skillRows = $this->orm->attackSkills->findByClassAndLevel($data["occupation"], $data["level"]);
    foreach($skillRows as $skillRow) {
      $skills[] = new CharacterAttackSkillDummy(new SkillAttackDummy($skillRow), 0);
    }
    unset($skillRow);
    $skillRows = $this->orm->specialSkills->findByClassAndLevel($data["occupation"], $data["level"]);
    foreach($skillRows as $skillRow) {
      $skills[] = new CharacterSpecialSkillDummy(new SkillSpecialDummy($skillRow), 0);
    }
    $this->getArenaNpcSkillsLevels($data, $skills);
    if($npc->weapon) {
      $weapon = $this->equipmentModel->view($npc->weapon->id);
      if($weapon) {
        $weapon->worn = true;
        $equipment[] = $weapon;
      }
    }
    $npc = new Character($data, $equipment, [], $skills);
    return $npc;
  }
  
  /**
   * Get amount of fights a player has fought today in arena
   *
   * @param int $uid
   * @return int
   */
  function getNumberOfTodayArenaFights(int $uid): int {
    $row = $this->orm->arenaFightsCount->getByCharacterAndDay($uid, date("d.m.Y"));
    if(is_null($row)) {
      return 0;
    } else {
      return $row->amount;
    }
  }
  
  /**
   * Increase amount of a player's fights in arena
   *
   * @param int $uid
   * @return void
   */
  function bumpNumberOfTodayArenaFights(int $uid): void {
    $day = date("d.m.Y");
    $row = $this->orm->arenaFightsCount->getByCharacterAndDay($uid, $day);
    if(is_null($row)) {
      $row = new ArenaFightCount;
      $this->orm->arenaFightsCount->attach($row);
      $row->character = $uid;
      $row->day = $day;
    } else {
      $row->amount++;
    }
    $this->orm->arenaFightsCount->persistAndFlush($row);
  }
}
?>