<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\ChatCommand;

/**
 * ChatCommandsProcessor
 *
 * @author Jakub Konečný
 */
class ChatCommandsProcessor {
  use \Nette\SmartObject;
  
  /** @var ChatCommand[] */
  protected $commands = [];
  
  /**
   * Add new  command
   *
   * @param string $name
   * @param callable $callback
   * @param array $parameters
   * @return void
   * @throws CommandNameAlreadyUsedException
   */
  function addCommand(ChatCommand $command) {
    if($this->hasCommand($command->name)) throw new CommandNameAlreadyUsedException("Command $command->name is already defined.");
    $this->commands[] = $command;
  }
  
  /**
   * Extract command from text
   *
   * @param string $text
   * @return string
   */
  function extractCommand($text) {
    if(substr($text, 0, 1) === "/" AND $this->hasCommand(substr($text, 1))) {
      return substr($text, 1);
    } else {
      return "";
    }
  }
  
  /**
   * Check whetever a command is defined
   *
   * @param string $name
   * @return bool
   */
  function hasCommand($name) {
    foreach($this->commands as $command) {
      if($command->name === $name) return true;
    }
    return false;
  }
  
  /**
   * Get command's definition
   *
   * @param string $name
   * @return ChatCommand
   * @throws CommandNotFoundException
   */
  function getCommand($name) {
    foreach($this->commands as $command) {
      if($command->name === $name) return $command;
    }
    throw new CommandNotFoundException;
  }
  
  /**
   * Execute a command
   *
   * @param string $name
   * @return string
   * @throws CommandNotFoundException
   */
  function executeCommand($name) {
    try {
      $command = $this->getCommand($name);
    } catch(CommandNotFoundException $e) {
      throw $e;
    }
    return $command->execute();
  }
}
?>