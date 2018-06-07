<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Combat\BaseSkill;
use HeroesofAbenez\Combat\SkillAttack;
use HeroesofAbenez\Combat\SkillSpecial;
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
  
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  
  public function __construct(\Nette\Caching\Cache $cache, ORM $orm, \Nette\Security\User $user) {
    $this->cache = $cache;
    $this->orm = $orm;
    $this->user = $user;
  }
  
  /**
   * Get list of attack skills
   * 
   * @return SkillAttack[]
   */
  public function getListOfAttackSkills(): array {
    $skillsList = $this->cache->load("attack_skills", function() {
      $return = [];
      $skills = $this->orm->attackSkills->findAll();
      /** @var \HeroesofAbenez\Orm\SkillAttack $skill */
      foreach($skills as $skill) {
        $return[$skill->id] = $skill->toDummy();
      }
      return $return;
    });
    return $skillsList;
  }
  
  public function getAttackSkill(int $id): ?SkillAttack {
    $skills = $this->getListOfAttackSkills();
    return Arrays::get($skills, $id, null);
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
    return new CharacterAttackSkillDummy($skill, $level);
  }
  
  /**
   * Get list of special skills
   * 
   * @return SkillSpecial[]
   */
  public function getListOfSpecialSkills(): array {
    $skillsList = $this->cache->load("special_skills", function() {
      $return = [];
      $skills = $this->orm->specialSkills->findAll();
      /** @var \HeroesofAbenez\Orm\SkillSpecial $skill */
      foreach($skills as $skill) {
        $return[$skill->id] = $skill->toDummy();
      }
      return $return;
    });
    return $skillsList;
  }
  
  public function getSpecialSkill(int $id): ?SkillSpecial {
    $skills = $this->getListOfSpecialSkills();
    return Arrays::get($skills, $id, null);
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
    return new CharacterSpecialSkillDummy($skill, $level);
  }
  
  /**
   * @return BaseCharacterSkill[]
   */
  public function getAvailableSkills(): array {
    $return = [];
    $skills = array_merge($this->getListOfAttackSkills(), $this->getListOfSpecialSkills());
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    /** @var BaseSkill $skill */
    foreach($skills as $skill) {
      if($skill->neededClass != $character->occupation->id) {
        continue;
      } elseif(!is_null($skill->neededSpecialization)) {
        if(is_null($character->specialization)) {
          continue;
        } elseif($skill->neededSpecialization != $character->specialization) {
          continue;
        }
      } elseif($skill->neededLevel > $character->level) {
        continue;
      }
      if($skill instanceof SkillAttack) {
        $return[] = $this->getCharacterAttackSkill($skill->id);
      } elseif($skill instanceof SkillSpecial) {
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
  public function trainSkill(int $id, string $type) {
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
    if($skill->skill->neededClass != $character->occupation->id) {
      throw new CannotLearnSkillException();
    } elseif($skill->skill->neededSpecialization) {
      if(is_null($character->specialization)) {
        throw new CannotLearnSkillException();
      } elseif($skill->skill->neededSpecialization != $character->specialization->id) {
        throw new CannotLearnSkillException();
      }
    } elseif($skill->skill->neededLevel > $character->level) {
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
    } elseif($type === "special") {
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