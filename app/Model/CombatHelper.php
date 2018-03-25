<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Combat\Character,
    HeroesofAbenez\Combat\CharacterAttackSkill,
    HeroesofAbenez\Combat\CharacterSpecialSkill,
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
  
  public function __construct(Profile $profileModel, Equipment $equipmentModel, Skills $skillsModel, ORM $orm) {
    $this->profileModel = $profileModel;
    $this->equipmentModel = $equipmentModel;
    $this->skillsModel = $skillsModel;
    $this->orm = $orm;
  }
  
  /**
   * Get data for specified player
   *
   * @throws OpponentNotFoundException
   */
  public function getPlayer(int $id): Character {
    $data = $equipment = $pets = [];
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
    foreach($character->equipment as $row) {
      if(!$row->worn) {
        continue;
      }
      /** @var \HeroesofAbenez\Orm\Equipment $item */
      $item = $this->equipmentModel->view($row->item->id);
      $item->worn = true;
      $equipment[] = $item->toCombatEquipment();
    }
    $skills = $this->skillsModel->getPlayerSkills($id);
    $player = new Character($data, $equipment, $pets, $skills);
    return $player;
  }
  
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
   * @throws OpponentNotFoundException
   */
  public function getArenaNpc($id): Character {
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
    $skills = $equipment = [];
    $skillRows = $this->orm->attackSkills->findByClassAndLevel($occupation, $data["level"]);
    foreach($skillRows as $skillRow) {
      $skills[] = new CharacterAttackSkill($skillRow->toDummy(), 0);
    }
    unset($skillRow);
    $skillRows = $this->orm->specialSkills->findByClassAndLevel($occupation, $data["level"]);
    foreach($skillRows as $skillRow) {
      $skills[] = new CharacterSpecialSkill($skillRow->toDummy(), 0);
    }
    $this->getArenaNpcSkillsLevels($data, $skills);
    if(!is_null($npc->weapon)) {
      $weapon = $this->equipmentModel->view($npc->weapon->id);
      if(!is_null($weapon)) {
        $weapon->worn = true;
        $equipment[] = $weapon->toCombatEquipment();
      }
    }
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