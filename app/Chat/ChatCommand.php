<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

/**
 * Chat Command
 *
 * @author Jakub Konečný
 * @property string $name
 */
abstract class ChatCommand {
  use \Nette\SmartObject;
  
  /** @var string */
  protected $name = "";
  
  /**
   * Defines default name for the chat command
   * The class' name has to follow XCommand pattern
   */
  public function getName(): string {
    if($this->name !== "") {
      return $this->name;
    }
    $className = join('', array_slice(explode('\\', static::class), -1));
    return strtolower(str_replace("Command", "", $className));
  }
  
  public function setName(string $name) {
    $this->name = $name;
  }
  
  abstract function execute(): string;
}
?>