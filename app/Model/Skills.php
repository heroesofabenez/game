<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\SkillAttack;
use HeroesofAbenez\Orm\SkillSpecial;
use HeroesofAbenez\Combat\BaseCharacterSkill;
use HeroesofAbenez\Combat\CharacterAttackSkill as CharacterAttackSkillDummy;
use HeroesofAbenez\Combat\CharacterSpecialSkill as CharacterSpecialSkillDummy;
use HeroesofAbenez\Orm\CharacterAttackSkill;
use HeroesofAbenez\Orm\CharacterSpecialSkill;

/**
 * Skills Model
 *
 * @author Jakub Konečný
 */
final class Skills {
  use \Nette\SmartObject;

  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  
  public function __construct(ORM $orm, \Nette\Security\User $user) {
    $this->orm = $orm;
    $this->user = $user;
  }
  
  /**
   * Get list of attack skills
   * 
   * @return SkillAttack[]
   */
  public function getListOfAttackSkills(): array {
    /** @var SkillAttack[] $skills */
    $skills = $this->orm->attackSkills->findAll()->fetchAll();
    return $skills;
  }
  
  public function getAttackSkill(int $id): ?SkillAttack {
    return $this->orm->attackSkills->getById($id);
  }
  
  public function getCharacterAttackSkill(int $skillId, int $userId = 0): ?CharacterAttackSkillDummy {
    if($userId === 0) {
      $userId = $this->user->id;
    }
    $skill = $this->getAttackSkill($skillId);
    if(is_null($skill)) {
      return null;
    }
    $row = $this->orm->characterAttackSkills->getByCharacterAndSkill($userId, $skillId);
    $level = 0;
    if(!is_null($row)) {
      $level = $row->level;
    }
    return new CharacterAttackSkillDummy($skill->toDummy(), $level);
  }
  
  /**
   * Get list of special skills
   * 
   * @return SkillSpecial[]
   */
  public function getListOfSpecialSkills(): array {
    /** @var SkillSpecial[] $skills */
    $skills = $this->orm->specialSkills->findAll()->fetchAll();
    return $skills;
  }
  
  public function getSpecialSkill(int $id): ?SkillSpecial {
    return $this->orm->specialSkills->getById($id);
  }
  
  public function getCharacterSpecialSkill(int $skillId, int $userId = 0): ?CharacterSpecialSkillDummy {
    if($userId === 0) {
      $userId = $this->user->id;
    }
    $skill = $this->getSpecialSkill($skillId);
    if(is_null($skill)) {
      return null;
    }
    $row = $this->orm->characterSpecialSkills->getByCharacterAndSkill($userId, $skillId);
    $level = 0;
    if(!is_null($row)) {
      $level = $row->level;
    }
    return new CharacterSpecialSkillDummy($skill->toDummy(), $level);
  }

  /**
   * @param SkillAttack|SkillSpecial $skill
   */
  protected function canLearnSkill($skill): bool {
    if($skill->neededClass->id != $this->user->identity->class) {
      return false;
    } elseif(!is_null($skill->neededSpecialization)) {
      if(is_null($this->user->identity->specialization)) {
        return false;
      } elseif($skill->neededSpecialization->id != $this->user->identity->specialization) {
        return false;
      }
    } elseif($skill->neededLevel > $this->user->identity->level) {
      return false;
    }
    return true;
  }

  /**
   * @return BaseCharacterSkill[]
   */
  public function getAvailableSkills(): array {
    $return = [];
    $skills = array_merge($this->getListOfAttackSkills(), $this->getListOfSpecialSkills());
    foreach($skills as $skill) {
      if(!$this->canLearnSkill($skill)) {
        continue;
      }
      if($skill instanceof SkillAttack) {
        $return[] = $this->getCharacterAttackSkill($skill->id);
      } else {
        $return[] = $this->getCharacterSpecialSkill($skill->id);
      }
    }
    return $return;
  }
  
  /**
   * Get amount of user's usable skill points
   */
  public function getSkillPoints(): int {
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    return $character->skillPoints;
  }
  
  /**
   * Learn new skill/improve existing one
   *
   * @throws InvalidSkillTypeException
   * @throws NoSkillPointsAvailableException
   * @throws SkillNotFoundException
   * @throws SkillMaxLevelReachedException
   * @throws CannotLearnSkillException
   */
  public function trainSkill(int $id, string $type): void {
    if(!in_array($type, ["attack", "special"], true)) {
      throw new InvalidSkillTypeException();
    } elseif($this->getSkillPoints() < 1) {
      throw new NoSkillPointsAvailableException();
    }
    $method = "getCharacter" . ucfirst($type) . "Skill";
    $skill = $this->$method($id);
    if(!$skill) {
      throw new SkillNotFoundException();
    } elseif($skill->level + 1 > $skill->skill->levels) {
      throw new SkillMaxLevelReachedException();
    }
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    if(!$this->canLearnSkill($skill)) {
      throw new CannotLearnSkillException();
    }
    if($type === "attack") {
      $record = $this->orm->characterAttackSkills->getByCharacterAndSkill($this->user->id, $id);
      if(is_null($record)) {
        $record = new CharacterAttackSkill();
        $this->orm->characterAttackSkills->attach($record);
        $record->character = $character;
        $record->skill = $id;
        $record->level = 0;
      }
      $record->level++;
      $record->character->skillPoints--;
      $this->orm->characterAttackSkills->persistAndFlush($record);
    } else {
      $record = $this->orm->characterSpecialSkills->getByCharacterAndSkill($this->user->id, $id);
      if(is_null($record)) {
        $record = new CharacterSpecialSkill();
        $this->orm->characterSpecialSkills->attach($record);
        $record->character = $character;
        $record->skill = $id;
        $record->level = 0;
      }
      $record->level++;
      $record->character->skillPoints--;
      $this->orm->characterSpecialSkills->persistAndFlush($record);
    }
  }
}
?>