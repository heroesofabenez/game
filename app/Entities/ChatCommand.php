<?php
namespace HeroesofAbenez\Entities;

/**
 * Chat Command
 *
 * @author Jakub Konečný
 */
abstract class ChatCommand extends BaseEntity {
  /** @var string */
  protected $name;
  
  function __construct($name = "") {
    $this->name = $name;
  }
  
  /**
   * @return string
   */
  abstract function execute();
}
?>