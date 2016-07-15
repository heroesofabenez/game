<?php
namespace HeroesofAbenez\Entities;

/**
 * Base Skill
 *
 * @author Jakub Konečný
 */
abstract class Skill extends BaseEntity {
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
  protected $target = "single";
  /** @var int */
  protected $levels;
}
?>