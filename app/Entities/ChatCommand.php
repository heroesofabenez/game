<?php
namespace HeroesofAbenez\Entities;

/**
 * Chat Command
 *
 * @author Jakub Konečný
 * @property string $name
 */
abstract class ChatCommand extends BaseEntity {
  /** @var string */
  protected $name;
  
  function __construct($name = "") {
    $this->name = $name;
  }
  
  /**
   * @param string $name
   */
  function setName(string $name) {
    $this->name = (string) $name;
  }
  
  /**
   * @return string
   */
  abstract function execute(): string;
}
?>