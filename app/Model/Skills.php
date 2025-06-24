<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\SkillAttack;
use HeroesofAbenez\Orm\SkillSpecial;
use HeroesofAbenez\Combat\BaseCharacterSkill;
use HeroesofAbenez\Orm\CharacterAttackSkill;
use HeroesofAbenez\Orm\CharacterSpecialSkill;

/**
 * Skills Model
 *
 * @author Jakub Konečný
 */
final class Skills {
  use \Nette\SmartObject;

  private ORM $orm;
  private \Nette\Security\User $user;
  
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
  
  public function getCharacterAttackSkill(int $skillId, int $userId = 0): ?CharacterAttackSkill {
    if($userId === 0) {
      $userId = $this->user->id;
    }
    $skill = $this->getAttackSkill($skillId);
    if($skill === null) {
      return null;
    }
    $row = $this->orm->characterAttackSkills->getByCharacterAndSkill($userId, $skillId);
    if($row === null) {
      $row = new CharacterAttackSkill();
      $row->level = 0;
      $row->skill = $skill;
    }
    return $row;
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
  
  public function getCharacterSpecialSkill(int $skillId, int $userId = 0): ?CharacterSpecialSkill {
    if($userId === 0) {
      $userId = $this->user->id;
    }
    $skill = $this->getSpecialSkill($skillId);
    if($skill === null) {
      return null;
    }
    $row = $this->orm->characterSpecialSkills->getByCharacterAndSkill($userId, $skillId);
    if($row === null) {
      $row = new CharacterSpecialSkill();
      $row->level = 0;
      $row->skill = $skill;
    }
    return $row;
  }

  /**
   * @param SkillAttack|SkillSpecial $skill
   */
  private function canLearnSkill($skill): bool {
    if($skill->neededClass->id !== $this->user->identity->class) {
      return false;
    } elseif($skill->neededSpecialization !== null) {
      if($this->user->identity->specialization === null) {
        return false;
      } elseif($skill->neededSpecialization->id !== $this->user->identity->specialization) {
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
      $s = null;
      if($skill instanceof SkillAttack) {
        $s = $this->getCharacterAttackSkill($skill->id);
      } else {
        $s = $this->getCharacterSpecialSkill($skill->id);
      }
      if($s !== null) {
        $return[] = $s;
      }
    }
    /** @var BaseCharacterSkill[] $return */
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
    /** @var CharacterAttackSkill|CharacterSpecialSkill|null $skill */
    $skill = $this->$method($id);
    if($skill === null) {
      throw new SkillNotFoundException();
    } elseif($skill->level + 1 > $skill->skill->levels) {
      throw new SkillMaxLevelReachedException();
    }
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    if(!$this->canLearnSkill($skill->skill)) {
      throw new CannotLearnSkillException();
    }
    if($type === "attack") {
      $record = $this->orm->characterAttackSkills->getByCharacterAndSkill($this->user->id, $id);
      if($record === null) {
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
      if($record === null) {
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