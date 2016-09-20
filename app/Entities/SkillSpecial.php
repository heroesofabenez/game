<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Skill Special
 *
 * @author Jakub Konečný
 */
class SkillSpecial extends Skill {
  /** @var string */
  protected $type;
  /** @var string */
  protected $stat;
  /** @var int */
  protected $value;
  /** @var int */
  protected $value_growth;
  /** @var int */
  protected $duration;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->getName() != "skills_specials") exit;
    $this->id = $row->id;
    $this->name = $row->name;
    $this->description = $row->description;
    $this->needed_class = $row->needed_class;
    $this->needed_specialization = $row->needed_specialization;
    $this->needed_level = $row->needed_level;
    $this->type = $row->type;
    $this->target = $row->target;
    $this->stat = $row->stat;
    $this->value = $row->value;
    $this->value_growth = $row->value_growth;
    $this->levels = $row->levels;
    $this->duration = $row->duration;
  }
  
  /**
   * @return int
   */
  function getCooldown(): int {
    return 5;
  }
}
?>