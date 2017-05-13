<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

use HeroesofAbenez\Entities\BaseEntity;

/**
 * Chat Command
 *
 * @author Jakub Konečný
 * @property string $name
 */
abstract class ChatCommand extends BaseEntity {
  /** @var string */
  protected $name = "";
  
  /**
   * Defines default name for the chat command
   * The class' name has to follow XCommand pattern
   *
   * @return string
   */
  function getName(): string {
    if($this->name !== "") {
      return $this->name;
    }
    $className = join('', array_slice(explode('\\', static::class), -1));
    return strtolower(str_replace("Command", "", $className));
  }
  
  /**
   * @param string $name
   */
  function setName(string $name) {
    $this->name = $name;
  }
  
  /**
   * @return string
   */
  abstract function execute(): string;
}
?>