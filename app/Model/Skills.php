<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\SkillAttack,
    HeroesofAbenez\Entities\SkillSpecial,
    HeroesofAbenez\Entities\CharacterSkillAttack,
    HeroesofAbenez\Entities\CharacterSkillSpecial,
    HeroesofAbenez\Entities\CharacterSkill;

/**
 * Skills Model
 *
 * @author Jakub Konečný
 */
class Skills {
  use \Nette\SmartObject;
  
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Security\User */
  protected $user;
  
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db, \Nette\Security\User $user) {
    $this->cache = $cache;
    $this->db = $db;
    $this->user = $user;
  }
  
  /**
   * Get list of attack skills
   * 
   * @return SkillAttack[]
   */
  function getListOfAttackSkills(): array {
    $skillsList = $this->cache->load("attack_skills");
    if($skillsList === NULL) {
      $skillsList = [];
      $skills = $this->db->table("skills_attacks");
      foreach($skills as $skill) {
        $skillsList[$skill->id] = new SkillAttack($skill);
      }
      $this->cache->save("attack_skills", $skillsList);
    }
    return $skillsList;
  }
  
  /**
   * @param int $id
   * @return SkillAttack|bool
   */
  function getAttackSkill(int $id) {
    $skills = $this->getListOfAttackSkills();
    $skill = Arrays::get($skills, $id, false);
    return $skill;
  }
  
  /**
   * @param int $skillId
   * @param int $userId
   * @return CharacterSkillAttack|bool
   */
  function getCharacterAttackSkill(int $skillId, int $userId = 0) {
    if($userId === 0) {
      $userId = $this->user->id;
    }
    $skill = $this->getAttackSkill($skillId);
    if(!$skill) {
      return false;
    }
    $where = ["character" => $userId, "skill" => $skillId];
    $result = $this->db->query("SELECT * FROM character_attack_skills WHERE ?", $where)->fetch();
    if($result) {
      $level = $result->level;
    } else {
      $level = 0;
    }
    return new CharacterSkillAttack($skill, $level);
  }
  
  /**
   * Get list of special skills
   * 
   * @return SkillSpecial[]
   */
  function getListOfSpecialSkills(): array {
    $skillsList = $this->cache->load("special_skills");
    if($skillsList === NULL) {
      $skillsList = [];
      $skills = $this->db->table("skills_specials");
      foreach($skills as $skill) {
        $skillsList[$skill->id] = new SkillSpecial($skill);
      }
      $this->cache->save("special_skills", $skillsList);
    }
    return $skillsList;
  }
  
  /**
   * @param int $id
   * @return SkillSpecial|bool
   */
  function getSpecialSkill(int $id) {
    $skills = $this->getListOfSpecialSkills();
    $skill = Arrays::get($skills, $id, false);
    return $skill;
  }
  
  /**
   * @param int $skillId
   * @param int $userId
   * @return CharacterSkillSpecial|bool
   */
  function getCharacterSpecialSkill(int $skillId, int $userId = 0) {
    if($userId === 0) {
      $userId = $this->user->id;
    }
    $skill = $this->getSpecialSkill($skillId);
    if(!$skill) {
      return false;
    }
    $where = ["character" => $userId, "skill" => $skillId];
    $result = $this->db->query("SELECT * FROM character_special_skills WHERE ?", $where)->fetch();
    if($result) {
      $level = $result->level;
    } else {
      $level = 0;
    }
    return new CharacterSkillSpecial($skill, $level);
  }
  
  /**
   * @return CharacterSkill[]
   */
  function getAvailableSkills(): array {
    $return = [];
    $skills = array_merge($this->getListOfAttackSkills(), $this->getListOfSpecialSkills());
    $char = $this->db->table("characters")->get($this->user->id);
    foreach($skills as $skill) {
      if($skill->needed_class != $char->occupation) {
        continue;
      } elseif($skill->needed_specialization AND $skill->needed_specialization != $char->specialization) {
        continue;
      } elseif($skill->needed_level > $char->level) {
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
   * @param int $uid
   * @return CharacterSkill[]
   */
  function getPlayerSkills(int $uid): array {
    $return = [];
    $skills = array_merge($this->getListOfAttackSkills(), $this->getListOfSpecialSkills());
    $char = $this->db->table("characters")->get($uid);
    foreach($skills as $skill) {
      if($skill->needed_class != $char->occupation) {
        continue;
      } elseif($skill->needed_specialization AND $skill->needed_specialization != $char->specialization) {
        continue;
      } elseif($skill->needed_level > $char->level) {
        continue;
      }
      if($skill instanceof SkillAttack) {
        $s = $this->getCharacterAttackSkill($skill->id, $uid);
      } elseif($skill instanceof SkillSpecial) {
        $s = $this->getCharacterSpecialSkill($skill->id, $uid);
      }
      if($s->level) {
        $return[] = $s;
      }
    }
    return $return;
  }
  
  /**
   * Get amount of user's usable skill points
   * 
   * @return int
   */
  function getSkillPoints(): int {
    return (int) $this->db->table("characters")->get($this->user->id)->skill_points;
  }
  
  /**
   * Learn new skill/improve existing one
   * 
   * @param int $id
   * @param string $type
   * @return void
   * @throws InvalidSkillTypeException
   * @throws NoSkillPointsAvailableException
   * @throws SkillNotFoundException
   * @throws SkillMaxLevelReachedException
   * @throws CannotLearnSkillException
   */
  function trainSkill(int $id, string $type) {
    if(!in_array($type, ["attack", "special"])) {
      throw new InvalidSkillTypeException;
    } elseif($this->getSkillPoints() < 1) {
      throw new NoSkillPointsAvailableException;
    }
    $method = "getCharacter" . ucfirst($type) . "Skill";
    $skill = $this->$method($id);
    if(!$skill) {
      throw new SkillNotFoundException;
    } elseif($skill->level +1 > $skill->skill->levels) {
      throw new SkillMaxLevelReachedException;
    }
    $char = $this->db->table("characters")->get($this->user->id);
    if($skill->skill->needed_class != $char->occupation) {
      throw new CannotLearnSkillException;
    } elseif($skill->skill->needed_specialization AND $skill->skill->needed_specialization != $char->specialization) {
      throw new CannotLearnSkillException;
    } elseif($skill->skill->needed_level > $char->level) {
      throw new CannotLearnSkillException;
    }
    $data1 = "skill_points=skill_points-1";
    $where1 = ["id" => $this->user->id];
    $this->db->query("UPDATE characters SET $data1 WHERE ?", $where1);
    if($skill->level === 0) {
      $data2 = ["character" => $this->user->id, "skill" => $id, "level" => 1];
      $this->db->query("INSERT INTO character_{$type}_skills", $data2);
    } else {
      $data2 = "level=level+1";
      $where2 = ["character" => $this->user->id, "skill" => $id];
      $this->db->query("UPDATE character_{$type}_skills SET $data2 WHERE ?", $where2);
    }
  }
}
?>