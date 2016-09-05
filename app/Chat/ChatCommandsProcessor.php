<?php
namespace HeroesofAbenez\Chat;

use HeroesofAbenez\Entities\ChatCommand,
    HeroesofAbenez\Model\CommandNotFoundException,
    HeroesofAbenez\Model\CommandNameAlreadyUsedException;

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
   * Add new command
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
   * Add an alias for already defined command
   * 
   * @param string $oldName
   * @param string $newName
   * @return void
   * @throws CommandNotFoundException
   * @throws CommandNameAlreadyUsedException
   */
  function addAlias($oldName, $newName) {
    try {
      $command = $this->getCommand($oldName);
    } catch(CommandNotFoundException $ex) {
      throw $e;
    }
    $new = clone $command;
    $new->name = $newName;
    try {
      $this->addCommand($new);
    } catch(CommandNameAlreadyUsedException $e) {
      throw $e;
    }
  }
  
  /**
   * Extract command from text
   *
   * @param string $text
   * @return string
   */
  function extractCommand($text) {
    if(substr($text, 0, 1) != "/") return "";
    if(!strpos($text, " ")) {
      $command = substr($text, 1);
    } else {
      $parts = explode(" ", substr($text, 1));
      $command = $parts[0];
    }
    if($this->hasCommand($command)) {
      return $command;
    }
    return "";
  }
  
  
  /**
   * Extract parameters from text
   * 
   * @param string $text
   * @return array
   */
  function extractParameters($text) {
    if(substr($text, 0, 1) != "/" OR !strpos($text, " ")) return [];
    $params = explode(" ", $text);
    unset($params[0]);
    return $params;
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
    throw new CommandNotFoundException("Command $name is not defined.");
  }
  
  /**
   * @param type $text
   * @return string|bool Result of the command/false when text text contains no (defined) command
   */
  function parse($text) {
    $commandName = $this->extractCommand($text);
    if($commandName === "") return false;
    $command = $this->getCommand($commandName);
    $params = $this->extractParameters($text);
    return call_user_func_array([$command, "execute"], $params);
  }
}
?>