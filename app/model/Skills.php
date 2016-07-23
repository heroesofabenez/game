<?php
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
  function getListOfAttackSkills() {
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
  function getAttackSkill($id) {
    $skills = $this->getListOfAttackSkills();
    $skill = Arrays::get($skills, $id, false);
    return $skill;
  }
  
  /**
   * @param int $id
   * @return CharacterSkillAttack|bool
   */
  function getCharacterAttackSkill($id) {
    $skill = $this->getAttackSkill($id);
    if(!$skill) return false;
    $level = 0;
    $where = ["character" => $this->user->id, "skill" => $id];
    $result = $this->db->query("SELECT * FROM character_attack_skills WHERE ?", $where)->fetch();
    if($result) $level = $result->level;
    return new CharacterSkillAttack($skill, $level);
  }
  
  /**
   * Get list of special skills
   * 
   * @return SkillSpecial[]
   */
  function getListOfSpecialSkills() {
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
  function getSpecialSkill($id) {
    $skills = $this->getListOfSpecialSkills();
    $skill = Arrays::get($skills, $id, false);
    return $skill;
  }
  
  /**
   * @param int $id
   * @return CharacterSkillAttack|bool
   */
  function getCharacterSpecialSkill($id) {
    $skill = $this->getSpecialSkill($id);
    if(!$skill) return false;
    $level = 0;
    $where = ["character" => $this->user->id, "skill" => $id];
    $result = $this->db->query("SELECT * FROM character_special_skills WHERE ?", $where)->fetch();
    if($result) $level = $result->level;
    return new CharacterSkillSpecial($skill, $level);
  }
  
  /**
   * @return CharacterSkill[]
   */
  function getAvailableSkills() {
    $return = [];
    $skills = array_merge($this->getListOfAttackSkills(), $this->getListOfSpecialSkills());
    $char = $this->db->table("characters")->get($this->user->id);
    foreach($skills as $skill) {
      if($skill->needed_class != $char->occupation) continue;
      elseif($skill->needed_specialization AND $skill->needed_specialization != $char->specialization) continue;
      elseif($skill->needed_level > $char->level) continue;
      if($skill instanceof SkillAttack) $return[] = $this->getCharacterAttackSkill($skill->id);
      elseif($skill instanceof SkillSpecial) $return[] = $this->getCharacterSpecialSkill($skill->id);
    }
    return $return;
  }
  
  /**
   * Get amount of user's usable skill points
   * 
   * @return int
   */
  function getSkillPoints() {
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
  function trainSkill($id, $type) {
    if(!in_array($type, ["attack", "special"])) throw new InvalidSkillTypeException;
    elseif($this->getSkillPoints() < 1) throw new NoSkillPointsAvailableException;
    $method = "getCharacter" . ucfirst($type) . "Skill";
    $skill = $this->$method($id);
    if(!$skill) throw new SkillNotFoundException;
    elseif($skill->level +1 > $skill->skill->levels) throw new SkillMaxLevelReachedException;
    $char = $this->db->table("characters")->get($this->user->id);
    if($skill->skill->needed_class != $char->occupation) throw new CannotLearnSkillException;
    elseif($skill->skill->needed_specialization AND $skill->skill->needed_specialization != $char->specialization) throw new CannotLearnSkillException;
    elseif($skill->skill->needed_level > $char->level) throw new CannotLearnSkillException;
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

class InvalidSkillTypeException extends \RuntimeException {
  
}

class SkillNotFoundException extends RecordNotFoundException {
  
}

class NoSkillPointsAvailableException extends AccessDenied {
  
}

class SkillMaxLevelReachedException extends AccessDenied {
  
}

class CannotLearnSkillException extends AccessDenied {
  
}
?>