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
    else return new CharacterSkillAttack($skill, 0);
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
    else return new CharacterSkillSpecial($skill, 0);
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
}
?>