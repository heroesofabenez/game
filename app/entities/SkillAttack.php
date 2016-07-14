<?php
namespace HeroesofAbenez\Entities;

/**
 * Attack skill
 *
 * @author Jakub Konečný
 */
class SkillAttack extends BaseEntity {
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $description;
  /** @var int */
  protected $needed_class;
  /** @var int */
  protected $needed_specialization;
  /** @var int */
  protected $needed_level;
  /** @var string */
  protected $base_damage;
  /** @var string */
  protected $damage_growth;
  /** @var int */
  protected $levels;
  /** @var string */
  protected $target = "single";
  /** @var int */
  protected $strikes = 1;
  /** @var string|NULL */
  protected $hit_rate = NULL;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->getName() != "skills_attacks") exit;
    $this->id = $row->id;
    $this->name = $row->name;
    $this->description = $row->description;
    $this->needed_class = $row->needed_class;
    $this->needed_specialization = $row->needed_specialization;
    $this->needed_level = $row->needed_level;
    $this->base_damage = $row->base_damage;
    $this->damage_growth = $row->damage_growth;
    $this->levels = $row->levels;
    $this->target = $row->target;
    $this->strikes = $row->strikes;
    $this->hit_rate = $row->hit_rate;
  }
}
?>