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
  
  /** @var \HeroesofAbenez\Model\Profile */
  protected $profileModel;
  /** @var \HeroesofAbenez\Model\Equipment */
  protected $equipmentModel;
  /** @var \HeroesofAbenez\Model\Skills */
  protected $skillsModel;
  /** @var \Nette\Database\Context */
  protected $db;
  
  function __construct(\HeroesofAbenez\Model\Profile $profileModel, \HeroesofAbenez\Model\Equipment $equipmentModel, \HeroesofAbenez\Model\Skills $skillsModel, \Nette\Database\Context $db) {
    $this->profileModel = $profileModel;
    $this->equipmentModel = $equipmentModel;
    $this->skillsModel = $skillsModel;
    $this->db = $db;
  }
  
  /**
   * Get data for specified player
   * 
   * @param int $id Player's id
   * @return Character
   * @throws OpponentNotFoundException
   */
  function getPlayer($id) {
    $data = $this->profileModel->view($id);
    if(!$data) throw new OpponentNotFoundException;
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
   * Get data for specified npc
   * 
   * @param int $id Npc's id
   * @return Character
   * @throws OpponentNotFoundException
   */
  function getArenaNpc($id) {
    $row = (array) $this->db->query("SELECT * FROM pve_arena_opponents WHERE id=$id")->fetch();
    if(count($row) === 1) throw new OpponentNotFoundException;
    $row["id"] = "pveArenaNpc" . $row["id"];
    $skills = $equimpent = [];
    $skillRows = $this->db->query("SELECT id FROM skills_attacks WHERE needed_class={$row["occupation"]} AND needed_level<={$row["level"]}");
    foreach($skillRows as $skillRow) {
      $skills[] = new CharacterSkillAttack(new SkillAttack($this->db->table("skills_attacks")->get($skillRow->id)), 1);
     }
    unset($skillRow);
    $skillRows = $this->db->query("SELECT id FROM skills_specials WHERE needed_class={$row["occupation"]} AND needed_level<={$row["level"]}");
    foreach($skillRows as $skillRow) {
      $skills[] = new CharacterSkillSpecial(new SkillSpecial($this->db->table("skills_specials")->get($skillRow->id)), 1);
    }
    if($row["weapon"]) {
      $weapon = $this->equipmentModel->view($row["weapon"]);
      if($weapon) {
        $weapon->worn = true;
        $equimpent[] = $weapon;
      }
    }
    $npc = new Character($row, $equimpent, [], $skills);
    return $npc;
  }
}

class OpponentNotFoundException extends \Exception {
  
}
?>
