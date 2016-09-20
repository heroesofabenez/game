<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Character,
    HeroesofAbenez\Entities\CharacterSkillAttack,
    HeroesofAbenez\Entities\SkillAttack,
    HeroesofAbenez\Entities\CharacterSkillSpecial,
    HeroesofAbenez\Entities\SkillSpecial;

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
  /** @var \Nette\Database\Context */
  protected $db;
  
  function __construct(Profile $profileModel, Equipment $equipmentModel, Skills $skillsModel, \Nette\Database\Context $db) {
    $this->profileModel = $profileModel;
    $this->equipmentModel = $equipmentModel;
    $this->skillsModel = $skillsModel;
    $this->db = $db;
  }
  
  /**
   * Get initiative formula for given class
   * 
   * @param int $classId
   * @return string
   */
  function getInitiativeFormula(int $classId): string {
    $class = $this->profileModel->getClass($classId);
    if(!$class) return "0";
    else return $class->initiative;
  }
  
  /**
   * Get data for specified player
   *
   * @param int $id Player's id
   * @return Character
   * @throws OpponentNotFoundException
   */
  function getPlayer(int $id): Character {
    $data = $this->profileModel->view($id);
    if(!$data) throw new OpponentNotFoundException;
    $data["initiative_formula"] = $this->getInitiativeFormula($data["occupation"]);
    $pets = $equipment = [];
    if($data["pet"]) $pets[] = $data["pet"];
    unset($data["pet"]);
    $equipmentRows = $this->db->table("character_equipment")
      ->where("character", $id)
      ->where("worn", 1);
    foreach($equipmentRows as $row) {
      $item = $this->equipmentModel->view($row->item);
      $item->worn = true;
      $equipment[] = $item;
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
  protected function getArenaNpcSkillsLevels(array $data, array &$skills) {
    if($data["level"] < 2) $skills = [$skills[0]];
    $skillPoints = $data["level"];
    for($i = 1; $skillPoints > 0; $i++) {
      foreach($skills as $skill) {
        if($skillPoints < 1) break;
        if($skill->level + 1 <= $skill->skill->levels) $skill->level++;
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
    $row = (array) $this->db->query("SELECT * FROM pve_arena_opponents WHERE id=$id")->fetch();
    if(count($row) === 1) throw new OpponentNotFoundException;
    $row["id"] = "pveArenaNpc" . $row["id"];
    $row["initiative_formula"] = $this->getInitiativeFormula($row["occupation"]);
    $skills = $equipment = [];
    $skillRows = $this->db->query("SELECT id FROM skills_attacks WHERE needed_class={$row["occupation"]} AND needed_level<={$row["level"]}");
    foreach($skillRows as $skillRow) {
      $skills[] = new CharacterSkillAttack(new SkillAttack($this->db->table("skills_attacks")->get($skillRow->id)), 0);
    }
    unset($skillRow);
    $skillRows = $this->db->query("SELECT id FROM skills_specials WHERE needed_class={$row["occupation"]} AND needed_level<={$row["level"]}");
    foreach($skillRows as $skillRow) {
      $skills[] = new CharacterSkillSpecial(new SkillSpecial($this->db->table("skills_specials")->get($skillRow->id)), 0);
    }
    $this->getArenaNpcSkillsLevels($row, $skills);
    if($row["weapon"]) {
      $weapon = $this->equipmentModel->view($row["weapon"]);
      if($weapon) {
        $weapon->worn = true;
        $equipment[] = $weapon;
      }
    }
    $npc = new Character($row, $equipment, [], $skills);
    return $npc;
  }
}
?>